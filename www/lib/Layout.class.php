<?php

include ("lib/ImageHandler.class.php");

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

		$k=count($files);
		if($k==0) {
			echo "No files found";
			return;
		}

		print '<ul>';

		for($i=0;$i<$k;$i++) {
			if($files[$i]["type"]=="directory")
				print '<li><a id="'.$i.'" href="'.$url."/".$files[$i]["name"].'"><img src="/directory.jpg" /></a></li>';
			else
				print '<li><a id="'.$i.'" href="'.$url."/".$files[$i]["name"].'#'.$i.'"><img src="/img'.$url."/".$files[$i]["name"].'?size=70" /></a></li>';
		}
		print '</ul>';
	}

	public function getImage($url, $size) {
		if($this->fileSystemHandler->isDirectory($url)) return;

		print '<img src="/img'.$url.'?size=600" />';
	}

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