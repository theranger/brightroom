<?php

include_once "ImageRenderer.php";

class ImagePNGRenderer implements ImageRenderer {

	private $img;

	public function loadFile(string $path): resource {
		$this->img = imagecreatefrompng($path);
		return $this->img;
	}

	public function outputImage(string $fileName) {
		if($fileName != NULL) header('Content-Type: image/png');

		imagepng($this->img, $fileName);
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
