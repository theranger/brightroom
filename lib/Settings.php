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

	public function __construct() {
		if(defined(DATA_DIR)) $this->dataDirectory = DATA_DIR;
		if(defined(IMG_PREFIX)) $this->imagePrefix = IMG_PREFIX;
		if(defined(GALLERY_URL)) $this->galleryURL = GALLERY_URL;
		if(defined(DOCUMENT_ROOT)) $this->documentRoot = DOCUMENT_ROOT;
		if(defined(CACHE_FOLDER)) $this->cacheFolder = CACHE_FOLDER;
		if(defined(FORCE_HTTPS)) $this->forceHTTPS = FORCE_HTTPS;
		if(defined(THUMBNAIL_SIZE)) $this->thumbnailSize = THUMBNAIL_SIZE;
		if(defined(IMAGE_SIZE)) $this->imageSize = IMAGE_SIZE;
		if(defined(BADGE_WIDTH)) $this->badgeWidth = BADGE_WIDTH;
		if(defined(BADGE_ELEMENT_COUNT)) $this->badgeElementCount = BADGE_ELEMENT_COUNT;
		if(defined(SHOW_EXIF)) $this->showExif = SHOW_EXIF;
		if(defined(OVERLAY_TITLE)) $this->overlayTitle = OVERLAY_TITLE;
		if(defined(README_FILE)) $this->readmeFile = README_FILE;
		if(defined(PAGINATION)) $this->pagination = PAGINATION;
		if(defined(ANCHOR_OFFSET)) $this->anchorOffset = ANCHOR_OFFSET;
		if(defined(VETO_FOLDERS)) $this->vetoFolders = VETO_FOLDERS;
		if(defined(PASSWD_FILE)) $this->passwordFile = PASSWD_FILE;
		if(defined(ACCESS_FILE)) $this->accessFile = ACCESS_FILE;
		if(defined(THEME)) $this->theme = THEME;
		if(defined(SALT)) $this->salt = SALT;
	}
}
