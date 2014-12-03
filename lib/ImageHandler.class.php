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
	
	public function assembleImage($fileSystemHandler, $directoryURL, $size, $defaultImage) {
		if(!$this->imageRenderer) return;
		$cachedImgPath = NULL;
		$cache = NULL;

		if(defined("CACHE_FOLDER")) {
			$cache = new ImageCache($fileSystemHandler->getFullPath($directoryURL).'/'.CACHE_FOLDER);
			
			if(!$cache->prepareCache()) return;
			$cachedImgName = $size.'_Badge.jpg';
			
			if($cache->inCache($cachedImgName)) {
				$cache->getFromCache($cachedImgName);
				return;
			}
			
			$cachedImgPath = $cache->getFullPath($cachedImgName);
		}
		
		$img = imagecreatetruecolor($size * 2, $size);

		if($defaultImage != null) {
			$default = $this->imageRenderer->loadFile($defaultImage);
			imagecopyresampled($img, $default, 0, 0, 0, 0, imagesx($img), imagesy($img), imagesx($default), imagesy($default));
		}
		
		$filesArray = $fileSystemHandler->getFilesArray($directoryURL);
		$posX = 0;
		$k = count($filesArray);
		
		for($i = 0; $i < $k; $i++) {
			if($posX > $size * 2) break;
			if($filesArray[$i]["folder"]) continue;

			//Badge must contain 3 images
			if(($k - $i) < 3) break;

			$orig = $this->imageRenderer->loadFile($fileSystemHandler->getFullPath($directoryURL.'/'.$filesArray[$i]["name"]));
			
			$origW = imagesx($orig);
			$origH = imagesy($orig);
			$ratio = $origH/$origW;
			
			if($origW > $origH) {
				// landscape
				$newW = $size;
				$newH = $size / $ratio;
			}
			else {
				// portrait
				$newW = $size;
				$newH = $size;
			}
			
			$offset = $origW/2;
			 
			imagecopyresampled($img, $orig, $posX, 0, $offset, 0, $newW-30, $newH, $origW-$offset, $origH);
			$posX += $newW-30;
		}
		
		$this->imageRenderer->setHandle($img);
		$this->imageRenderer->outputImage($cachedImgPath);
		if($cache != NULL) $cache->getFromCache($cachedImgName);
	}
}


?>