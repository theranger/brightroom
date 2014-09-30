<?php

class URLParser {

	private $url;
	private $fsh;

	private $fullImage = false;
	private $isValid = false;

	public function __construct($url, $fsh) {
		$this->fsh = $fsh;
		
		$this->parseURL($url);
	}

	public function getURL() {
		return $this->url;
	}
	
	public function getDocumentRoot() {
		return defined("DOCUMENT_ROOT")?DOCUMENT_ROOT:"";
	}
	
	public function getImagePrefix() {
		if(defined("GALLERY_URL")) return GALLERY_URL.IMG_PREFIX;
		if(defined("DOCUMENT_ROOT")) return DOCUMENT_ROOT.IMG_PREFIX;
		
		return IMG_PREFIX;
	}
	
	public function getThemePrefix() {
		if(defined("GALLERY_URL")) return GALLERY_URL."/themes";
		if(defined("DOCUMENT_ROOT")) return DOCUMENT_ROOT."/themes";
		
		return "/themes";
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

	private function parseURL($url) {
		//Strip prefix
		if(strncmp($url, $this->getDocumentRoot(), strlen($this->getDocumentRoot())) == 0) {
			$url = substr($url, strlen($this->getDocumentRoot()));
		}
		
		//Check for IMG prefix
		if(strncmp($url, IMG_PREFIX, strlen(IMG_PREFIX)) == 0) {
			$this->url = $this->fsh->clearPath(substr($url, strlen(IMG_PREFIX)));
			$this->fullImage = true;
			return;
		}

		//Clear path from nasty things
		$url = $this->fsh->clearPath($url);
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

		$this->url = rtrim($url, "/");
		$this->isValid = true;
	}
}

?>