<?php

include_once "ImageJPEGRenderer.php";
include_once "ImagePNGRenderer.php";
include_once "io/ImageCache.php";
include_once "net/ContentType.php";

class ImageHandler {

	private $imageRenderer;
	private $settings;
	private $file;

	public function __construct(string $mimeType, Settings $settings, File $file) {
		$this->settings = $settings;
		$this->file = $file;

		switch ($mimeType) {
			case ContentType::JPEG:
				$this->imageRenderer = new ImageJPEGRenderer();
				break;
			case ContentType::PNG:
				$this->imageRenderer = new ImagePNGRenderer();
				break;
		}
	}

	public function resizeImage(int $size, int $orientation) {
		if (!$this->imageRenderer) return;

		$cachedImgPath = NULL;
		$cache = NULL;
		$cachedImgName = $size . '_' . basename($this->file->getPath());

		if (!empty($this->settings->cacheFolder)) {
			$cache = new ImageCache($this->file->getFolder()->getPath(), $this->settings->cacheFolder);
			if (!$cache->exists()) return;

			if ($cache->inCache($cachedImgName)) {
				$imagestat = stat($this->file->getPath());
				$cachestat = stat(dirname($this->file->getPath()) . '/' . $this->settings->cacheFolder . '/' . $cachedImgName);

				if ($imagestat['mtime'] < $cachestat['mtime']) {
					$cache->read($cachedImgName);
					return;
				}

				//If we are here, original image mtime was newer than cached image mtime
				//Invalidate stale cache
				$cache->invalidateImage('*_' . basename($this->file->getPath()));
			}

			$cachedImgPath = $cache->getImagePath($cachedImgName);
		}

		$orig = $this->imageRenderer->loadFile($this->file->getPath());
		if ($orientation != 0) $orig = imagerotate($orig, $orientation, 0);

		$origH = imagesx($orig);
		$origW = imagesy($orig);
		$ratio = $origW / $origH;

		$newW = $size;
		$newH = $size / $ratio;

		$img = imagecreatetruecolor($newH, $newW);
		imagecopyresampled($img, $orig, 0, 0, 0, 0, $newH, $newW, $origH, $origW);

		$this->imageRenderer->setHandle($img);
		$this->imageRenderer->outputImage($cachedImgPath);

		if ($cache != NULL) $cache->read($cachedImgName);
	}
}

