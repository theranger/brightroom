<?php

include_once "ImageJPEGRenderer.php";
include_once "ImagePNGRenderer.php";
include_once "io/ImageCache.php";
include_once "net/ContentType.php";

class ImageHandler {

	private $imageRenderer;
	private $settings;
	private $fileSystemHandler;

	public function __construct(string $mimeType, Settings $settings, FileSystemHandler $fileSystemHandler) {
		$this->settings = $settings;
		$this->fileSystemHandler = $fileSystemHandler;

		switch($mimeType) {
			case ContentType::JPEG:
				$this->imageRenderer = new ImageJPEGRenderer();
				break;
			case ContentType::PNG:
				$this->imageRenderer = new ImagePNGRenderer();
				break;
		}
	}

	public function resizeImage(string $path, int $size, int $orientation) {
		if(!$this->imageRenderer) return;

		$cachedImgPath = NULL;
		$cache = NULL;
		$cachedImgName = $size.'_'.basename($path);

		if(!empty($this->settings->cacheFolder)) {
			$cache = new ImageCache(dirname($path).'/'.$this->settings->cacheFolder);
			if(!$cache->prepareCache()) return;

			if($cache->inCache($cachedImgName)) {
				$imagestat = stat($path);
				$cachestat = stat(dirname($path).'/'.$this->settings->cacheFolder.'/'.$cachedImgName);

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

	public function assembleImage(FileSystemHandler $fileSystemHandler, string $directoryURL, int $bdgH, string $defaultImage) {
		if(!$this->imageRenderer) return;

		$cachedImgPath = NULL;
		$cache = NULL;
		$cachedImgName = $bdgH.'_Badge.jpg';

		if(!empty($this->settings->cacheFolder)) {
			$cache = new ImageCache($fileSystemHandler->getFullPath($directoryURL).'/'.$this->settings->cacheFolder);
			if(!$cache->prepareCache()) return;

			if($cache->inCache($cachedImgName)) {
				$cache->getFromCache($cachedImgName);
				return;
			}

			$cachedImgPath = $cache->getFullPath($cachedImgName);
		}

		$img = imagecreatetruecolor($this->settings->badgeWidth, $bdgH);

		if(!empty($defaultImage)) {
			$default = $this->imageRenderer->loadFile($defaultImage);
			imagecopyresampled($img, $default, 0, 0, 0, 0, imagesx($img), imagesy($img), imagesx($default), imagesy($default));
		}

		$filesArray = $fileSystemHandler->getFilesArray($directoryURL);
		$k = count($filesArray);
		$posX = 0;
		$dstH = $bdgH;
		$dstW = round($this->settings->badgeWidth/$this->settings->badgeElementCount); // Number of displayed images

		for($i = 0; $i < $k; $i++) {
			//Current pos is moved behind badge width, stop
			if($posX > $this->settings->badgeWidth) break;

			//Skip folders
			if($filesArray[$i]["folder"]) continue;

			//Directory must contain at least images needed for badge generation
			if(($posX == 0) && ($k - $i) < $this->settings->badgeElementCount) break;

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

