<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-08
 *
 */
class Settings {

	public $version = "1.2";

	public $thumbnailSize = 100;

	public $imageSize = 600;

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
		foreach($settings as $key => $value) {
			$this->$key = $value;
		}
	}

	public function getImagePrefix(): string {
		if(!empty($this->galleryURL)) return $this->galleryURL.$this->imagePrefix;
		if(!empty($this->documentRoot)) return $this->documentRoot.$this->imagePrefix;
		return $this->imagePrefix;
	}

	public function getThemePrefix(): string {
		if(!empty($this->galleryURL)) return $this->galleryURL."/themes";
		if(!empty($this->documentRoot)) return $this->documentRoot."/themes";
		return "/themes";
	}
}
