<?php

include_once "Request.php";
include_once "Response.php";
include_once "controllers/Folder.php";
include_once "controllers/Image.php";
include_once "controllers/Text.php";

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

		switch($request->getRequestType()) {
			case RequestType::UNKNOWN:
				$fileSystemHandler = new FileSystemHandler($this->settings->dataDirectory);
				$request->updateType($fileSystemHandler);
				if ($request->getRequestType() != RequestType::UNKNOWN) return $this->route($request);
				error_log($request->getURL().": Unknown request type");
				return (new Response($request))->render(ResponseType::BAD_REQUEST);

			case RequestType::INVALID:
				error_log($request->getURL().": Requested file not found");
				return (new Response($request))->render(ResponseType::NOT_FOUND);

			case RequestType::IMAGE_FILE:
				$fileSystemHandler = new FileSystemHandler($this->settings->dataDirectory);
				$session = new Session($fileSystemHandler, $this->settings);
				$imageController = new Image($session, $this->settings, $fileSystemHandler);
				return $imageController->get($request);

			case RequestType::IMAGE_FOLDER:
				$fileSystemHandler = new FileSystemHandler($this->settings->dataDirectory);
				$session = new Session($fileSystemHandler, $this->settings);
				$folderController = new Folder($session, $this->settings, $fileSystemHandler);
				return $folderController->listing($request);

			case RequestType::THEME_FILE:
				$fileSystemHandler = new FileSystemHandler(getcwd());
				$session = new Session($fileSystemHandler, $this->settings);
				$fileController = new Text($session, $this->settings, $fileSystemHandler);
				return $fileController->get($request);
		}

		error_log($request->getURL().": Access denied");
		return (new Response($request))->render(ResponseType::FORBIDDEN);
	}
}
