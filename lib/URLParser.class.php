<?php

class URLParser {

	private $url;
	private $fsh;

	private $fullImage = false;
	private $isValid = false;

	public function __construct($url, $fsh) {
		$this->url = $url;
		$this->fsh = $fsh;

		$this->parseURL();
	}

	public function getURL() {
		return $this->url;
	}

	public function isFullImage() {
		return $this->fullImage;
	}

	public function isValid() {
		return $this->isValid;
	}

	public function isDirectory() {
		return $this->fsh->isDirectory($this->url);
	}

	public function isRoot() {
		return trim($this->url,"/") == '';
	}

	public function getDirectory() {
		if($this->isDirectory()) return $this->url;

		return dirname($this->url);
	}

	public function getImage() {
		if($this->isDirectory()) return null;

		return basename($this->url);
	}

	private function parseURL() {
		//Check for IMG prefix
		if(strncmp($this->url, IMG_PREFIX, strlen(IMG_PREFIX)) == 0) {
			$this->url = $this->fsh->clearPath(substr($this->url, strlen(IMG_PREFIX)));
			$this->fullImage = true;
			return;
		}

		//Clear path from nasty things
		$url = $this->fsh->clearPath($_GET["q"]);
		$file = basename($url);

		//Chcek if the URL is allowed
		if(!$this->fsh->exists($url) ||
			(defined("CACHE_FOLDER") && $file == CACHE_FOLDER) ||
			(defined("DEF_PASSWD_FILE") && $file == DEF_PASSWD_FILE) ||
			(defined("PASSWD_FILE") && $file == PASSWD_FILE) ||
			(defined("DEF_ACCESS_FILE") && $file == DEF_ACCESS_FILE) ||
			(defined("ACCESS_FILE") && $file == ACCESS_FILE) ||
			(defined("VETO_FOLDERS") && strpos(VETO_FOLDERS, '/'.$file.'/') !== false)
		) {
			echo "Item does not exist or is not readable";
			return;
		}

		$this->url = rtrim($this->url, "/");
		$this->isValid = true;
	}
}

?>