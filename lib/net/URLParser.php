<?php

class URLParser {

	private $settings;
	private $url;

	public function __construct(string $url, Settings &$settings) {
		$this->settings = $settings;

		// Cleanup the bad things
		$url = preg_replace('/\w+\/\.\.\//', '', $url);

		// Strip prefix
		if(strncmp($url, $this->settings->documentRoot, strlen($this->settings->documentRoot)) == 0) {
			$this->url = substr($url, strlen($this->settings->documentRoot));
		}
	}

	public function getURL(): string {
		return $this->url;
	}

	public function getImagePrefix(): string {
		if(!empty($this->settings->galleryURL)) return $this->settings->galleryURL.$this->settings->imagePrefix;
		if(!empty($this->settings->documentRoot)) return $this->settings->documentRoot.$this->settings->imagePrefix;
		return $this->settings->imagePrefix;
	}

	public function getThemePrefix(): string {
		if(!empty($this->settings->galleryURL)) return $this->settings->galleryURL."/themes";
		if(!empty($this->settings->documentRoot)) return $this->settings->documentRoot."/themes";
		return "/themes";
	}

	public function isRoot(): bool {
		return trim($this->url, "/") == '';
	}

	public function getResourceName(): string {
		return basename($this->url);
	}
}
