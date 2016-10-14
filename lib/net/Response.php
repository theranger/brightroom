<?php

include_once "ResponseType.php";
include_once "ui/UI.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Response {

	private $settings;
	private $session;

	public function __construct(Settings $settings, Session $session) {
		$this->settings = $settings;
		$this->session = $session;
	}

	public function render(int $responseCode, string $includeFile = ""): Response {
		http_response_code($responseCode);
		if (empty($includeFile)) return $this;

		if (!file_exists($includeFile)) {
			error_log($includeFile.": File cannot be loaded");
			http_response_code(ResponseType::INTERNAL_SERVER_ERROR);
			return $this;
		}

		new UI($this->settings, $this->session);
		include_once $includeFile;
		return $this;
	}
}
