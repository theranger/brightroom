<?php

class URLParser {

	private $fsh;
	private $settings;
	private $url = "";
	private $fullImage = false;
	private $isValid = false;

	public function __construct(string $url, FileSystemHandler $fsh, Settings $settings) {
		$this->fsh = $fsh;
		$this->settings = $settings;
		$this->parseURL($url);
	}

	public function getURL(): string {
		return $this->url;
	}

	public function getDocumentRoot(): string {
		return $this->settings->documentRoot;
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

	public function isFullImage(): bool {
		return $this->fullImage;
	}

	public function isValid(): bool {
		return $this->isValid;
	}

	public function isDirectory(): bool {
		return $this->fsh->isDirectory($this->url);
	}

	public function isRoot(): bool {
		return trim($this->url,"/") == '';
	}

	public function getDirectory(): string {
		if($this->isDirectory()) return $this->url;

		return dirname($this->url);
	}

	public function getImage(): string {
		if($this->isDirectory()) return null;

		return basename($this->url);
	}

	private function parseURL(string $url) {
		//Strip prefix
		if(strncmp($url, $this->getDocumentRoot(), strlen($this->getDocumentRoot())) == 0) {
			$url = substr($url, strlen($this->getDocumentRoot()));
		}

		//Check for IMG prefix
		if(strncmp($url, $this->settings->imagePrefix, strlen($this->settings->imagePrefix)) == 0) {
			$this->url = $this->fsh->clearPath(substr($url, strlen($this->settings->imagePrefix)));
			$this->fullImage = true;
			return;
		}

		//Clear path from nasty things
		$url = $this->fsh->clearPath($url);
		$file = basename($url);

		//Check if the URL is allowed
		if(!$this->fsh->exists($url) ||
			($this->settings->cacheFolder && $file == $this->settings->cacheFolder) ||
			($this->settings->passwordFile && $file == $this->settings->passwordFile) ||
			($this->settings->accessFile && $file == $this->settings->accessFile) ||
			($this->settings->vetoFolders && strpos($this->settings->vetoFolders, '/'.$file.'/') !== false)
		) {
			echo "Item does not exist or is not readable";
			return;
		}

		$this->url = rtrim($url, "/");
		$this->isValid = true;
	}
}
