<?php

include "ImageJPEGRenderer.class.php";
include "ImagePNGRenderer.class.php";
include "ImageCache.class.php";

class ImageHandler {

	private $imageRenderer;
	private $badgeElementCount;
	private $badgeWidth;

	public function __construct($mimeType) {
		$this->badgeElementCount = defined("BADGE_ELEMENT_COUNT")?BADGE_ELEMENT_COUNT:DEF_BADGE_ELEMENT_COUNT;
		$this->badgeWidth = defined("BADGE_WIDTH")?BADGE_WIDTH:DEF_BADGE_WIDTH;

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

	public function assembleImage($fileSystemHandler, $directoryURL, $bdgH, $defaultImage) {
		if(!$this->imageRenderer) return;
		$cachedImgPath = NULL;
		$cache = NULL;

		if(defined("CACHE_FOLDER")) {
			$cache = new ImageCache($fileSystemHandler->getFullPath($directoryURL).'/'.CACHE_FOLDER);

			if(!$cache->prepareCache()) return;
			$cachedImgName = $bdgH.'_Badge.jpg';

			if($cache->inCache($cachedImgName)) {
				$cache->getFromCache($cachedImgName);
				return;
			}

			$cachedImgPath = $cache->getFullPath($cachedImgName);
		}

		$img = imagecreatetruecolor($this->badgeWidth, $bdgH);

		if($defaultImage != null) {
			$default = $this->imageRenderer->loadFile($defaultImage);
			imagecopyresampled($img, $default, 0, 0, 0, 0, imagesx($img), imagesy($img), imagesx($default), imagesy($default));
		}

		$filesArray = $fileSystemHandler->getFilesArray($directoryURL);
		$k = count($filesArray);
		$posX = 0;
		$dstH = $bdgH;
		$dstW = round($this->badgeWidth/$this->badgeElementCount); // Number of displayed images

		for($i = 0; $i < $k; $i++) {
			//Current pos is moved behind badge width, stop
			if($posX > $this->badgeWidth) break;

			//Skip folders
			if($filesArray[$i]["folder"]) continue;

			//Directory must contain at least images needed for badge generation
			if(($posX == 0) && ($k - $i) < $this->badgeElementCount) break;

			$orig = $this->imageRenderer->loadFile($fileSystemHandler->getFullPath($directoryURL.'/'.$filesArray[$i]["name"]));
			$origH = imagesy($orig);
			$origW = ($dstW*$origH)/$dstH;

			imagecopyresampled(
				$img, $orig,		//dst, src
				$posX, 0,			//dst_x, dst_y
				0, 0,				//src_x, src_y
				$dstW, $dstH,		//dst_w, dst_h
				$origW, $origH		//src_w, src_h
			);

			$posX += $dstW;
		}

		$this->imageRenderer->setHandle($img);
		$this->imageRenderer->outputImage($cachedImgPath);
		if($cache != NULL) $cache->getFromCache($cachedImgName);
	}
}

