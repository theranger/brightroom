<?php

class ImageCache {

	private $fileSystemHandler;

	public function __construct(string $cacheDir) {
		$this->fileSystemHandler = new FileSystemHandler($cacheDir);
	}

	public function getFromCache(string $imgName): bool {
		if(!$this->fileSystemHandler->exists($imgName)) return false;

		return $this->fileSystemHandler->getFile($imgName);
	}

	public function putToCache(string $imgName, resource $imgData): bool {
		if(!$this->prepareCache()) return false;

		return $this->fileSystemHandler->saveFile($imgName, $imgData);
	}

	public function inCache(string $imgName): bool {
		return $this->fileSystemHandler->exists($imgName);
	}

	public function prepareCache(): bool {
		if(!$this->fileSystemHandler->exists()) {
			if(!$this->fileSystemHandler->createDirectory("",0775))
				return false;
		}

		return true;
	}

	public function invalidateCache(): bool {
		if(!$this->fileSystemHandler->exists()) return false;
		if(!$this->fileSystemHandler->removeDirectory()) return false;

		return true;
	}

	public function invalidateImage(string $path): bool {
		if(!$this->fileSystemHandler->exists()) return false;
		if(!$this->fileSystemHandler->removeFile($path)) return false;

		return true;
	}

	public function getFullPath(string $imgName): string {
		return $this->fileSystemHandler->getFullPath($imgName);
	}
}