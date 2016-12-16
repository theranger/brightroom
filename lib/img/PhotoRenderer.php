<?php
/**
 * Copyright 2016 The Ranger <ranger@risk.ee>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types = 1);

include_once "io/ImageCache.php";
include_once "net/ContentType.php";
include_once "GenericRenderer.php";
include_once "types/JPEGImage.php";
include_once "types/PNGImage.php";

class PhotoRenderer implements GenericRenderer {

	private $imageRenderer;
	private $settings;
	private $file;

	public function __construct(Settings $settings, File $file) {
		$this->settings = $settings;
		$this->file = $file;

		switch ($file->getType()) {
			case ContentType::JPEG:
				$this->imageRenderer = new JPEGImage();
				break;
			case ContentType::PNG:
				$this->imageRenderer = new PNGImage();
				break;
		}
	}

	public function render() {
		// TODO: Implement render() method.
	}

	public function resizeImage(int $size, int $orientation) {
		if (!$this->imageRenderer) return;

		$cachedImgPath = null;
		$cache = null;
		$cachedImgName = $size.'_'.basename($this->file->getPath());

		if (!empty($this->settings->cacheFolder)) {
			$cache = new ImageCache($this->file->getFolder()->getPath(), $this->settings->cacheFolder);
			if (!$cache->exists()) return;

			if ($cache->inCache($cachedImgName)) {
				$imagestat = stat($this->file->getPath());
				$cachestat = stat(dirname($this->file->getPath()).'/'.$this->settings->cacheFolder.'/'.$cachedImgName);

				if ($imagestat['mtime'] < $cachestat['mtime']) {
					$cache->read($cachedImgName);
					return;
				}

				//If we are here, original image mtime was newer than cached image mtime
				//Invalidate stale cache
				$cache->invalidateImage('*_'.basename($this->file->getPath()));
			}

			$cachedImgPath = $cache->getImagePath($cachedImgName);
		}

		$orig = $this->imageRenderer->loadFile($this->file->getPath());
		if ($orientation != 0) $orig = imagerotate($orig, $orientation, 0);

		$origH = imagesx($orig);
		$origW = imagesy($orig);
		$ratio = $origW / $origH;

		$newW = $size;
		$newH = (int)($size / $ratio);

		$img = imagecreatetruecolor($newH, $newW);
		imagecopyresampled($img, $orig, 0, 0, 0, 0, $newH, $newW, $origH, $origW);

		$this->imageRenderer->setHandle($img);
		$this->imageRenderer->outputImage($cachedImgPath);

		if ($cache != null) $cache->read($cachedImgName);
	}
}

