<?php

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
			header("Location: https://" . $request->getURL());
			die();
		}

		switch($request->getType()) {
			case RequestType::UNKNOWN:
				return new Response(ResponseType::BAD_REQUEST);

			case RequestType::IMAGE_FILE:
				return new Response(ResponseType::OK);

			case RequestType::IMAGE_FOLDER:
				return new Response(ResponseType::OK);
		}

		return new Response(ResponseType::FORBIDDEN);
	}
}
