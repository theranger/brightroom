<?php

class ImageCache {
	
	private $fileSystemHandler;
	
	public function __construct($cacheDir) {
		$this->fileSystemHandler = new FileSystemHandler($cacheDir);
	}
	
	public function getFromCache($imgName) {
		if(!$this->fileSystemHandler->exists($imgName)) return false;

		return $this->fileSystemHandler->getFile($imgName);
	}
	
	public function putToCache($imgName, $imgData) {
		if(!$this->prepareCache()) return false;
		
		$this->fileSystemHandler->saveFile($imgName, $imgData);
	}
	
	public function prepareCache() {
		if(!$this->fileSystemHandler->exists()) {
			if(!$this->fileSystemHandler->createDirectory())
				return false;
		}
		
		return true;
	}
	
	public function getFullPath($imgName) {
		return $this->fileSystemHandler->getFullPath($imgName);
	}
}

?>