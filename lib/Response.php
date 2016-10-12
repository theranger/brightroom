<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Response {

	public function __construct(int $responseCode, string $includeFile = "") {
		http_response_code($responseCode);
		if (!empty($includeFile)) include $includeFile;
	}
}
