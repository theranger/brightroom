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
		if ($this->settings->forceHTTPS == true && !$request->isSecure()) {
			error_log($request->getURL() . ": Secure connection forced, redirecting to HTTPS");
			header("Location: https://" . $request->getURL());
			die();
		}

		$fileSystem = new FileSystem($this->settings->dataDirectory, $request->getURL());

		switch ($request->getRequestType()) {
			case RequestType::UNKNOWN:
				$request->elaborateType($fileSystem);
				return $this->route($request);

			case RequestType::INVALID:
				error_log($request->getURL() . ": Requested file not found");
				return (new Response($request))->render(ResponseCode::NOT_FOUND);

			case RequestType::IMAGE_FILE:
			case RequestType::THUMBNAIL_FILE:
				if ($fileSystem->getFile() == null) $this->renderResponse($request, ResponseCode::BAD_REQUEST);

				$session = new Session($fileSystem, $this->settings);
				$imageController = new Image($session, $this->settings, $fileSystem);
				return $imageController->get($request);

			case RequestType::IMAGE_FOLDER:
				$session = new Session($fileSystem, $this->settings);
				$collectionController = new Collection($session, $this->settings, $fileSystem);
				return $collectionController->listing($request);

			case RequestType::THEME_FILE:
				$fileSystem = new FileSystem(getcwd(), $request->getURL());
				if ($fileSystem->getFile() == null) return $this->renderResponse($request, ResponseCode::BAD_REQUEST);

				$session = new Session($fileSystem, $this->settings);
				$fileController = new Text($session, $this->settings, $fileSystem->getFile());
				return $fileController->get($request);
		}

		error_log($request->getURL() . ": Access denied");
		return $this->renderResponse($request, ResponseCode::FORBIDDEN);
	}

	private function renderResponse(Request $request, int $responseCode): Response {
		return (new Response($request))->render($responseCode);
	}
}
