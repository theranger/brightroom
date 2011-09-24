<?php

include ("ImageJPEGRenderer.class.php");

interface ImageRenderer {
	public function loadFile($path);
	public function outputImage();
	public function getHandle();
	public function setHandle($img);
}

class ImageHandler {

	private $imageRenderer;

	public function __construct($mimeType) {
		switch($mimeType) {
			case "image/jpeg":
				$this->imageRenderer = new ImageJPEGRenderer();
		}
	}

	public function resizeImage($path, $height, $width) {
		if(!$this->imageRenderer) return;

		$orig = $this->imageRenderer->loadFile($path);
		$img = imagecreatetruecolor($height,$width);
		imagecopyresampled($img, $orig, 0, 0, 0, 0, $height, $width, imagesx($orig), imagesy($orig));

		$this->imageRenderer->setHandle($img);
		$this->imageRenderer->outputImage();
	}
}


?>