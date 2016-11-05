<?php

include_once "Request.php";
include_once "Response.php";
include_once "controllers/Folder.php";
include_once "controllers/Image.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Router {

	private $settings;
	private $session;
	private $fileSystemHandler;

	public function __construct(Settings $settings, FileSystemHandler $fileSystemHandler) {
		$this->settings = $settings;
		$this->fileSystemHandler = $fileSystemHandler;
		$this->session = new Session($fileSystemHandler, $settings);
	}

	public function route(Request $request): Response {
		if($this->settings->forceHTTPS == true && !$request->isSecure()) {
			error_log($request->getURL().": Secure connection forced, redirecting to HTTPS");
			header("Location: https://" . $request->getURL());
			die();
		}

		switch($request->getRequestType()) {
			case RequestType::UNKNOWN:
				error_log($request->getURL().": Unknown request type");
				return (new Response($request))->render(ResponseType::BAD_REQUEST);

			case RequestType::INVALID:
				error_log($request->getURL().": Requested file not found");
				return (new Response($request))->render(ResponseType::NOT_FOUND);

			case RequestType::IMAGE_FILE:
				$imageController = new Image($this->session, $this->settings, $this->fileSystemHandler);
				return $imageController->get($request);

			case RequestType::IMAGE_FOLDER:
				$folderController = new Folder($this->session, $this->settings, $this->fileSystemHandler);
				return $folderController->listing($request);
		}

		error_log($request->getURL().": Access denied");
		return (new Response($request))->render(ResponseType::FORBIDDEN);
	}
}
