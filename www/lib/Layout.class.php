<?php

include ("lib/ImageHandler.class.php");

class Layout {

	private $fileSystemHandler;

	public function __construct($fileSystemHandler) {
		$this->fileSystemHandler = $fileSystemHandler;
	}

	public function folderListing($url) {
		if(!$this->fileSystemHandler->isDirectory($url)) {
			$url = dirname($url);
		}

		$files = $this->fileSystemHandler->getFilesArray($url);

		$k=count($files);
		if($k==0) {
			echo "No files found";
			return;
		}

		print '<ul>';

		for($i=0;$i<$k;$i++) {
			print '<li><a href="'.$url."/".$files[$i].'"><img src="/img'.$url."/".$files[$i].'?size=70" /></a></li>';
		}
		print '</ul>';
	}

	public function getImage($url, $size) {
		if($this->fileSystemHandler->isDirectory($url)) return;

		print '<img src="/img'.$url.'" width="'.$size.'" />';
	}

	public function getFile($url, $size) {
		if($size) {
			$ih = new ImageHandler($this->fileSystemHandler->getMimeType($url));
			$ih->resizeImage($this->fileSystemHandler->getFullPath($url), $size, $size);
		}
		else {
			$this->fileSystemHandler->getFile($url);
		}
	}

}

?>