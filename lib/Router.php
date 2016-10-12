<?php

include_once "Request.php";
include_once "Response.php";

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
		if($this->settings->forceHTTPS == true && !isset($_SERVER["HTTPS"])) {
			error_log($request->getURL().": Secure connection forced, redirecting to HTTPS");
			header("Location: https://" . $request->getURL());
			die();
		}

		switch($request->getType()) {
			case RequestType::UNKNOWN:
				error_log($request->getURL().": Unknown request type");
				return new Response(ResponseType::BAD_REQUEST);

			case RequestType::INVALID:
				error_log($request->getURL().": Requested file not found");
				return new Response(ResponseType::NOT_FOUND);

			case RequestType::IMAGE_FILE:
				return new Response(ResponseType::OK, "themes/".$this->settings->theme."/single.php");

			case RequestType::IMAGE_FOLDER:
				echo "Showing image folder";
				return new Response(ResponseType::OK, "themes/".$this->settings->theme."/listing.php");
		}

		error_log($request->getURL().": Access denied");
		return new Response(ResponseType::FORBIDDEN);
	}
}
