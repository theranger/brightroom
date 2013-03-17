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
	
	public function getTheme() {
		return defined("THEME")?THEME:DEF_THEME;
	}
	
	public function printThemeURL() {
		print "/themes/".$this->getTheme();
	}
	
	public function setURLParser($urlParser) {
		$this->urlParser = $urlParser;
		
		$url = $this->urlParser->getURL();
		$this->isImage = !$this->fileSystemHandler->isDirectory($url);			
	}

	public function folderListing($url = null) {
		if($url == null) $url = $this->urlParser->getDirectory();
		$files = $this->fileSystemHandler->getFilesArray($url);
		
		print '<div class="imagelist">';
		
		// If url is not empty, we are in a subgallery. Show link to parent gallery
		if(!empty($url))
			$this->renderImage('/themes/'.$this->getTheme().'/images/upfolder.png', dirname($url), null, "..");
		
		$k=count($files);
		for($i=0;$i<$k;$i++) {
			
			//Anchor some images backwards, this way some previous images can also be seen from listing
			$anchor = $files[$i-3>=0?$i-3:0]["name"];
			$name = $files[$i]["name"];
			
			if($files[$i]["type"]=="directory")
				$this->renderImage('/themes/'.$this->getTheme().'/images/directory.jpg', $url."/".$name, null, $name);
			elseif($files[$i]["type"]=="image")
				$this->renderImage('/img'.$url.'/'.$name.'?size='.$this->thumbnailSize, $url."/".$name, $anchor, $name);
			elseif($this->imagesOnly == false)
				$this->renderImage('/img'.$url.'/'.$name.'?size='.$this->thumbnailSize, $url."/".$name, $anchor, $name);
		}
		print '</div>';
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
	
	private function renderImage($imageURL, $linkURL, $anchorName, $imageText) {
		print '<div class="image">';
		
		if($anchorName == null)
			print '<a href="'.$linkURL.'"><img src="'.$imageURL.'" /></a>';
		else
			print '<a name="'.basename($linkURL).'" href="'.$linkURL.'#'.$anchorName.'"><img src="'.$imageURL.'" /></a>';
		
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