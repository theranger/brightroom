<?php

include ("ImageHandler.class.php");
include ("ExifParser.class.php");
include ("defaults.inc.php");

class Layout {

	private $fileSystemHandler;
	
	// Settings from config files
	private $imagesOnly;
	private $thumbnailSize;
	private $imageSize;
	private $showExif;
	private $readmeFile;
	private $urlParser;
	private $exifParser;
	private $isImage;
	
	public function __construct($fileSystemHandler) {
		$this->fileSystemHandler = $fileSystemHandler;
		
		$this->imagesOnly = defined("SHOW_IMAGES_ONLY")?SHOW_IMAGES_ONLY:DEF_SHOW_IMAGES_ONLY;
		$this->thumbnailSize = defined("THUMBNAIL_SIZE") && is_numeric("THUMBNAIL_SIZE")?THUMBNAIL_SIZE:DEF_THUMBNAIL_SIZE;
		$this->imageSize = defined("IMAGE_SIZE") && is_numeric("IMAGE_SIZE")?IMAGE_SIZE:DEF_IMAGE_SIZE;
		$this->showExif = defined("SHOW_EXIF")?SHOW_EXIF:DEF_SHOW_EXIF;
		$this->readmeFile = defined("README_FILE")?README_FILE:DEF_README_FILE;
	}
	
	public function isShowExif() {
		return $this->showExif;
	}
	
	public function isImage() {
		return $this->isImage;
	}
	
	public function getExif() {
		if($this->exifParser == null) {
			if(!$this->isImage) return;
					
			$this->exifParser = new ExifParser($this->fileSystemHandler->getFullPath($this->urlParser->getURL()));
		}
			
		return $this->exifParser;
	}
	
	public function setURLParser($urlParser) {
		$this->urlParser = $urlParser;
		
		$url = $this->urlParser->getURL();
		$this->isImage = !$this->fileSystemHandler->isDirectory($url);			
	}

	public function folderListing($url = null) {
		if($url == null) $url = $this->urlParser->getDirectory();
		$files = $this->fileSystemHandler->getFilesArray($url);
		
		print '<ul>';
		
		// If url is not empty, we are in a subgallery. Show link to parent gallery
		if(!empty($url)) {
			print '<li><a href="../'.dirname($url).'"><img src="/upfolder.png" /></a></li>';
		}
		
		$k=count($files);
		for($i=0;$i<$k;$i++) {
			if($files[$i]["type"]=="directory") {
				print '<li><a href="'.$url."/".$files[$i]["name"].'"><img src="/directory.jpg" /></a><br />';
				print $this->fileSystemHandler->readFile($url."/".$files[$i]["name"]."/".$this->readmeFile);
				print '</li>';
			}
			elseif($files[$i]["type"]=="image")
				print '<li><a href="/'.$url."/".$files[$i]["name"].'"><img src="/img'.$url."/".$files[$i]["name"].'?size='.$this->thumbnailSize.'" /></a></li>';
			elseif($this->imagesOnly == false)
				print '<li><a href="/'.$url."/".$files[$i]["name"].'"><img src="/img'.$url."/".$files[$i]["name"].'?size='.$this->thumbnailSize.'" /></a></li>';
		}
		print '</ul>';
	}

	public function getImage($size, $url = null) {
		if($url == null) $url = $this->urlParser->getURL();
		if($this->fileSystemHandler->isDirectory($url)) return;
		
		$mimeType = dirname($this->fileSystemHandler->getMimeType($url));
		if($this->imagesOnly == true && $mimeType != "image") return;
		
		print '<img src="/img'.$url.'?size='.$this->imageSize.'" />';
	}
	
	public function readFile($url) {
		return $this->fileSystemHandler->readFile($url);
	}

	// Always returns Content-Type: image/...
	// Not for regular HTML output!
	public function getFile($url, $size) {
		if(is_numeric($size) && $size > 0) {
			$ih = new ImageHandler($this->fileSystemHandler->getMimeType($url));
			$ih->resizeImage($this->fileSystemHandler->getFullPath($url), $size);
		}
		else {
			$this->fileSystemHandler->getFile($url);
		}
	}

}

?>