<?php

include_once "ResponseCode.php";
include_once "ui/UI.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Response {

	private $request;

	public function __construct(Request $request) {
		$this->request = $request;
	}

	public function render(int $responseCode, string $includeFile = ""): Response {
		http_response_code($responseCode);
		if (empty($includeFile)) return $this;

		if (!file_exists($includeFile)) {
			error_log($includeFile.": File cannot be loaded, working directory " . getcwd());
			http_response_code(ResponseCode::INTERNAL_SERVER_ERROR);
			return $this;
		}

		include_once $includeFile;
		return $this;
	}

	public function asJson(int $responseCode, $data): Response {
		http_response_code($responseCode);
		header("Content-Type: " . ContentType::JSON);
		if (empty($data)) return $this;

		print json_encode($data);
		return $this;
	}

	public function asPlain(int $responseCode, $data): Response {
		http_response_code($responseCode);
		header("Content-Type: " . ContentType::PLAIN);

		print_r($data);
		return $this;
	}

	public function asType(int $responseCode, string $contentType): Response {
		http_response_code($responseCode);
		header("Content-Type: " . $contentType);
		return $this;
	}
}
