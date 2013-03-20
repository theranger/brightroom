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
	private $isRoot;
	
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
	
	public function isRoot() {
		return $this->isRoot;
	}
	
	public function getExif() {
		if($this->exifParser == null) {
			if(!$this->isImage) return;
					
			$this->exifParser = new ExifParser($this->fileSystemHandler->getFullPath($this->urlParser->getURL()));
		}
			
		return $this->exifParser;
	}
	
	public function getTheme() {
		return defined("THEME")?THEME:DEF_THEME;
	}
	
	public function printThemeURL() {
		print "/themes/".$this->getTheme();
	}
	
	public function printBreadcrumb() {
		
		print '<a href="/" class="breadcrumb">http://'.$_SERVER["SERVER_NAME"].'</a>';
		
		$url="";
		$path = explode("/",$this->urlParser->getURL());
		foreach($path as $el) {
			if(empty($el)) continue;
	
			$url.='/'.$el;
			print '<a href="'.$url.'" class="breadcrumb">/'.$el.'</a>';
		}
	}
	
	public function setURLParser($urlParser) {
		$this->urlParser = $urlParser;
		
		$url = $this->urlParser->getURL();
		$this->isImage = !$this->fileSystemHandler->isDirectory($url);
		$this->isRoot = $this->urlParser->isRoot();
	}

	public function printFolderListing($folders = true, $files = true) {
		$directory = $this->urlParser->getDirectory();
		$file = $this->urlParser->getImage();
		$items = $this->fileSystemHandler->getFilesArray($directory);
		
		print '<div class="imagelist">';
		
		// If url is not empty, we are in a subgallery. Show link to parent gallery
		if($folders == true && !empty($directory))
			$this->renderImage('/themes/'.$this->getTheme().'/images/upfolder.png', dirname($directory), null, "..", false);
		
		$k=count($items);
		for($i=0;$i<$k;$i++) {
			
			//Anchor some images backwards, this way some previous images can also be seen from listing
			$anchor = $items[$i-3>=0?$i-3:0]["name"];
			$name = $items[$i]["name"];
			
			if($items[$i]["type"]=="directory" && $folders == true)
				$this->renderImage('/themes/'.$this->getTheme().'/images/directory.jpg', $directory."/".$name, null, $name, $name == $file);
			elseif($items[$i]["type"]=="image" && $files == true)
				$this->renderImage('/img'.$directory.'/'.$name.'?size='.$this->thumbnailSize, $directory."/".$name, $anchor, null, $name == $file);
			elseif($this->imagesOnly == false && $files == true)
				$this->renderImage('/img'.$directory.'/'.$name.'?size='.$this->thumbnailSize, $directory."/".$name, $anchor, $name, $name == $file);
		}
		print '</div>';
	}

	public function getImage($size, $url = null) {
		if($url == null) $url = $this->urlParser->getURL();
		if($this->fileSystemHandler->isDirectory($url)) return;
		
		$mimeType = dirname($this->fileSystemHandler->getMimeType($url));
		if($this->imagesOnly == true && $mimeType != "image") return;
		
		$previousFile = $this->fileSystemHandler->getPreviousFile($this->urlParser->getDirectory(), $this->urlParser->getImage());
		$nextFile = $this->fileSystemHandler->getNextFile($this->urlParser->getDirectory(), $this->urlParser->getImage());
		
		print '<div class="single">';
		print '<img src="/img'.$url.'?size='.$this->imageSize.'" />';
		print '<a class="previous" href="'.$previousFile.'"></a>';
		print '<a class="next" href="'.$nextFile.'"></a>';
		print '</div>';
	}
	
	public function readFile($url) {
		return $this->fileSystemHandler->readFile($url);
	}

	// Always returns Content-Type: image/...
	// Not for regular HTML output!
	public function getFile($url, $size) {
		$mimeType = $this->fileSystemHandler->getMimeType($url);
		header("Content-Type:".$mimeType."\r\n");
		
		if(is_numeric($size) && $size > 0) {
			$ih = new ImageHandler($mimeType);
			$ih->resizeImage($this->fileSystemHandler->getFullPath($url), $size);
		}
		else {
			$this->fileSystemHandler->getFile($url);
		}
	}
	
	private function renderImage($imageURL, $linkURL, $anchorName, $imageText, $isCurrent) {
		print '<div class="image '.($isCurrent?"selected":"").'">';
		
		if($anchorName == null)
			print '<a href="'.$linkURL.'"><img src="'.$imageURL.'" /></a>';
		else
			print '<a class="image" name="'.basename($linkURL).'" href="'.$linkURL.'#'.$anchorName.'"><img src="'.$imageURL.'" /></a>';
		
		print $imageText;
		print '</div>';
	}

	public function printReadme() {
		print $this->fileSystemHandler->readFile($this->urlParser->getURL()."/".$this->readmeFile);
	}
	
	public function printFileCount() {
		print $this->fileSystemHandler->getFileCount($this->urlParser->getURL());
	}
	
	public function printDirectorySize() {
		print $this->fileSystemHandler->getDriectorySizeHuman($this->urlParser->getURL());
	}
}

?>