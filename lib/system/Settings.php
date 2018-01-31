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

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-08
 *
 */
class Settings {

	public $version = "2.1.1";

	public $thumbnailSize = 100;

	public $imageSize = 0;

	public $badgeFile = ".badge";

	public $badgeWidth = 200;

	public $badgeElementCount = 3;

	public $showExif = false;

	public $showImagesOnly = true;

	public $readmeFile = "readme.html";

	public $pagination = 200;

	public $theme = "default";

	public $anchorOffset = 3;

	public $overlayTitle = false;

	public $passwordFile = "galpasswd.txt";

	public $accessFile = "galaccess.txt";

	public $forceHTTPS = false;

	public $sessionName = "SFG-SESSION";

	public $dataDirectory = "files";

	public $imagePrefix = "/img";

	public $galleryURL = "";

	public $documentRoot = "";

	public $cacheFolder = "";

	public $vetoFolders = "/@eaDir/";

	public $salt = "";

	public function __construct(array &$settings) {
		foreach ($settings as $key => $value) {
			$this->$key = $value;
		}
	}

	public function getImagePrefix(): string {
		if (!empty($this->galleryURL)) return $this->galleryURL.$this->imagePrefix;
		if (!empty($this->documentRoot)) return $this->documentRoot.$this->imagePrefix;
		return $this->imagePrefix;
	}

	public function getThemePrefix(): string {
		if (!empty($this->galleryURL)) return $this->galleryURL."/themes";
		if (!empty($this->documentRoot)) return $this->documentRoot."/themes";
		return "/themes";
	}
}
