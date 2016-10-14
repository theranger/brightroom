<?php

include_once "Request.php";
include_once "Response.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Router {

	private $settings;
	private $session;

	public function __construct(Settings $settings, FileSystemHandler $fileSystemHandler) {
		$this->settings = $settings;
		$this->session = new Session($fileSystemHandler, $settings);
	}

	public function route(Request $request): Response {
		if($this->settings->forceHTTPS == true && !isset($_SERVER["HTTPS"])) {
			error_log($request->getURL().": Secure connection forced, redirecting to HTTPS");
			header("Location: https://" . $request->getURL());
			die();
		}

		$response = new Response($this->settings, $this->session);

		switch($request->getType()) {
			case RequestType::UNKNOWN:
				error_log($request->getURL().": Unknown request type");
				return $response->render(ResponseType::BAD_REQUEST);

			case RequestType::INVALID:
				error_log($request->getURL().": Requested file not found");
				return $response->render(ResponseType::NOT_FOUND);

			case RequestType::IMAGE_FILE:
				if ($this->session->authorize($request->getURL()))
					return $response->render(ResponseType::OK, "themes/".$this->settings->theme."/single.php");

				error_log($request->getURL().": Unauthorized");
				return $response->render(ResponseType::UNAUTHORIZED);

			case RequestType::IMAGE_FOLDER:
				if ($this->session->authorize($request->getURL()))
					return $response->render(ResponseType::OK, "themes/".$this->settings->theme."/listing.php");

				error_log($request->getURL().": Unauthorized");
				return $response->render(ResponseType::UNAUTHORIZED);
		}

		error_log($request->getURL().": Access denied");
		return $response->render(ResponseType::FORBIDDEN);
	}
}
