<?php

include_once "Request.php";
include_once "Response.php";
include_once "controllers/Collection.php";
include_once "controllers/Image.php";
include_once "controllers/Text.php";
include_once "io/FileSystem.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Router {

	private $settings;

	public function __construct(Settings $settings) {
		$this->settings = $settings;
	}

	public function route(Request $request): Response {
		if($this->settings->forceHTTPS == true && !$request->isSecure()) {
			error_log($request->getURL().": Secure connection forced, redirecting to HTTPS");
			header("Location: https://" . $request->getURL());
			die();
		}

		$fileSystem = new FileSystem($this->settings->dataDirectory, $request->getURL());

		switch($request->getRequestType()) {
			case RequestType::UNKNOWN:
				$request->elaborateType($fileSystem);
				return $this->route($request);

			case RequestType::INVALID:
				error_log($request->getURL().": Requested file not found");
				return (new Response($request))->render(ResponseCode::NOT_FOUND);

			case RequestType::IMAGE_FILE:
			case RequestType::THUMBNAIL_FILE:
				$file = $fileSystem->createFile();
				if ($file == null) $this->renderResponse($request, ResponseCode::BAD_REQUEST);

				$session = new Session($file->getFolder(), $this->settings);
				$imageController = new Image($session, $this->settings, $file);
				return $imageController->get($request);

			case RequestType::IMAGE_FOLDER:
				$folder = $fileSystem->createFolder();
				$session = new Session($folder, $this->settings);
				$collectionController = new Collection($session, $this->settings, $folder);
				return $collectionController->listing($request);

			case RequestType::THEME_FILE:
				$file = (new FileSystem(getcwd(), $request->getURL()))->createFile();
				if ($file == null) return $this->renderResponse($request, ResponseCode::BAD_REQUEST);

				$session = new Session($file->getFolder(), $this->settings);
				$fileController = new Text($session, $this->settings, $file);
				return $fileController->get($request);
		}

		error_log($request->getURL().": Access denied");
		return $this->renderResponse($request, ResponseCode::FORBIDDEN);
	}

	private function renderResponse(Request $request, int $responseCode): Response {
		return (new Response($request))->render($responseCode);
	}
}
