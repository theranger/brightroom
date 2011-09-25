<?php

include ("ImageHandler.class.php");
include ("ExifParser.class.php");
include ("defaults.inc.php");

class Layout {

	private $fileSystemHandler;
	
	public function __construct($fileSystemHandler) {
		$this->fileSystemHandler = $fileSystemHandler;
	}

	public function folderListing($url) {
		$url=rtrim($url,'/');
		
		if(!$this->fileSystemHandler->isDirectory($url)) {
			$url = dirname($url);
		}

		$files = $this->fileSystemHandler->getFilesArray($url);
		$thumbnailSize = defined("THUMBNAIL_SIZE") && is_numeric("THUMBNAIL_SIZE")?THUMBNAIL_SIZE:DEF_THUMBNAIL_SIZE;

		print '<ul>';

		// If url is not empty, we are in a subgallery. Show link to parent gallery
		if(!empty($url)) {
			print '<li><a href="'.dirname($url).'"><img src="/upfolder.png" /></a></li>';
		}
		
		$k=count($files);
		for($i=0;$i<$k;$i++) {
			if($files[$i]["type"]=="directory")
				print '<li><a href="'.$url."/".$files[$i]["name"].'"><img src="/directory.jpg" /></a></li>';
			else
				print '<li><a id="'.$i.'" href="'.$url."/".$files[$i]["name"].'#'.$i.'"><img src="/img'.$url."/".$files[$i]["name"].'?size='.$thumbnailSize.'" /></a></li>';
		}
		print '</ul>';
	}

	public function getImage($url, $size) {
		if($this->fileSystemHandler->isDirectory($url)) return;

		$imageSize = defined("IMAGE_SIZE") && is_numeric("IMAGE_SIZE")?IMAGE_SIZE:DEF_IMAGE_SIZE;
		$showExif = defined("SHOW_EXIF")?SHOW_EXIF:DEF_SHOW_EXIF;
		
		if($showExif == true) {
			$exifParser = new ExifParser($this->fileSystemHandler->getFullPath($url));
			print '<h1>'.$exifParser->getTitle().'</h1>';
		}
		
		print '<img src="/img'.$url.'?size='.$imageSize.'" /><br />';
		
		if($showExif == true) {
			print 'File size: '.round($exifParser->getFileSize()/1024).' kB <br />';
			print $exifParser->getDescription().'<br />';
			print '<i>'.$exifParser->getComment().'</i><br />';
		}
		
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