<?php

include ("ImageJPEGRenderer.class.php");
include ("ImagePNGRenderer.class.php");
include ("ImageCache.class.php");

interface ImageRenderer {
	public function loadFile($path);
	public function outputImage($img);
	public function getHandle();
	public function setHandle($img);
}

class ImageHandler {

	private $imageRenderer;

	public function __construct($mimeType) {
		switch($mimeType) {
			case "image/jpeg":
				$this->imageRenderer = new ImageJPEGRenderer();
				break;
			case "image/png":
				$this->imageRenderer = new ImagePNGRenderer();
				break;
		}
	}

	public function resizeImage($path, $size, $orientation) {
		if(!$this->imageRenderer) return;
		$cachedImgPath = NULL;
		$cache = NULL;

		if(defined("CACHE_FOLDER")) {
			$cache = new ImageCache(dirname($path).'/'.CACHE_FOLDER);

			if(!$cache->prepareCache()) return;
			$cachedImgName = $size.'_'.basename($path);

			if($cache->inCache($cachedImgName)) {
				$imagestat = stat($path);
				$cachestat = stat(dirname($path).'/'.CACHE_FOLDER.'/'.$cachedImgName);

				if($imagestat['mtime'] < $cachestat['mtime']) {
					$cache->getFromCache($cachedImgName);
					return;
				}

				//If we are here, original image mtime was newer than cached image mtime
				//Invalidate stale cache
				$cache->invalidateImage('*_'.basename($path));
			}

			$cachedImgPath = $cache->getFullPath($cachedImgName);
		}

		$orig = $this->imageRenderer->loadFile($path);
		if($orientation != 0) $orig = imagerotate($orig, $orientation, 0);

		$origH = imagesx($orig);
		$origW = imagesy($orig);
		$ratio = $origW/$origH;

		$newW = $size;
		$newH = $size / $ratio;

		$img = imagecreatetruecolor($newH,$newW);
		imagecopyresampled($img, $orig, 0, 0, 0, 0, $newH, $newW, $origH, $origW);

		$this->imageRenderer->setHandle($img);
		$this->imageRenderer->outputImage($cachedImgPath);

		if($cache != NULL) $cache->getFromCache($cachedImgName);
	}
}


?>