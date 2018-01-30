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

class ThumbnailRenderer implements GenericRenderer {

	private $image;
	private $settings;
	private $file;

	public function __construct(Settings $settings, File $file) {
		$this->settings = $settings;
		$this->file = $file;

		switch ($file->getType()) {
			case ContentType::JPEG:
				$this->image = new JPEGImage();
				break;
			case ContentType::PNG:
				$this->image = new PNGImage();
				break;
		}
	}

	public function render(int $size = 0, int $orientation = 0) {

		// Image size not set and orientation matches, return file as-is
		if ($size == 0 && $orientation == 0) {
			$this->file->read();
			return;
		}

		if (!$this->image) return;

		$cachedImgPath = null;
		$cache = null;
		$cachedImgName = $size.'_'.basename($this->file->getPath());

		if (!empty($this->settings->cacheFolder)) {

			// If cache folder path is absolute, treat it as out of tree location
			if ($this->settings->cacheFolder[0] === '/')
				// Use image folder URL for out of tree caching since this maps 1:1 to actual relative path anyway
				$cache = new ImageCache($this->settings->cacheFolder, $this->file->getFolder()->getURL());
			else
				$cache = new ImageCache($this->file->getFolder()->getPath(), $this->settings->cacheFolder);

			if (!$cache->exists()) return;

			if ($cache->inCache($cachedImgName)) {
				$imagestat = stat($this->file->getPath());
				$cachestat = stat($cache->getPath().'/'.$cachedImgName);

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

		$orig = $this->image->loadFile($this->file->getPath());
		if ($orientation != 0) $orig = imagerotate($orig, $orientation, 0);

		// Nothing to resize, but image has been rotated.
		// Do not save this in cache as it might not be cleaned up properly when EXIF info changes
		// as cache is not invalidated when orientation is set to zero
		if ($size == 0) {
			$this->image->setHandle($orig);
			$this->image->outputImage();
			return;
		}

		$origH = imagesx($orig);
		$origW = imagesy($orig);
		$ratio = $origW / $origH;

		$newW = $size;
		$newH = (int)($size / $ratio);

		$img = imagecreatetruecolor($newH, $newW);
		imagecopyresampled($img, $orig, 0, 0, 0, 0, $newH, $newW, $origH, $origW);

		$this->image->setHandle($img);
		$this->image->saveImage($cachedImgPath);

		if ($cache != null) $cache->read($cachedImgName);
	}
}

