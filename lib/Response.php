<?php

include_once "ResponseType.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Response {

	public function __construct(int $responseCode, string $includeFile = "") {
		http_response_code($responseCode);
		if (empty($includeFile)) return;

		if (!file_exists($includeFile)) {
			error_log($includeFile.": File cannot be loaded");
			http_response_code(ResponseType::INTERNAL_SERVER_ERROR);
			return;
		}

		include_once $includeFile;
	}
}
