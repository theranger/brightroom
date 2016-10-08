<?php

include_once "ImageRenderer.php";

class ImageJPEGRenderer implements ImageRenderer {

	private $img;

	public function loadFile(string $path): resource {
		$this->img = imagecreatefromjpeg($path);
		return $this->img;
	}

	public function outputImage(string $fileName) {
		if($fileName != NULL) header('Content-Type: image/jpeg');

		imagejpeg($this->img, $fileName);
		imagedestroy($this->img);
	}

	public function getHandle(): resource {
		return $this->img;
	}

	public function setHandle(resource $img) {
		imagedestroy($this->img);
		$this->img = $img;
	}
}
