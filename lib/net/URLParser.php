<?php

class URLParser {

	private static $urlPatterns = array('/index\.php\//','/\w+\/\.\.\//');

	private $settings;
	private $url;

	public function __construct(string $url, Settings &$settings) {
		$this->settings = $settings;

		// Cleanup the bad things
		$url = preg_replace(self::$urlPatterns, '', urldecode($url));

		// Strip prefix
		if(strncmp($url, $this->settings->documentRoot, strlen($this->settings->documentRoot)) == 0) {
			$url = substr($url, strlen($this->settings->documentRoot));
		}

		$this->url = trim($url, "/");
	}

	public function getURL(): string {
		return $this->url;
	}

	public function isRoot(): bool {
		return trim($this->url, "/") == '';
	}

	public function getResourceName(): string {
		return basename($this->url);
	}
}
