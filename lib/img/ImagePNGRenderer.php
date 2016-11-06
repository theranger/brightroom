<?php

include_once "ImageRenderer.php";

class ImagePNGRenderer implements ImageRenderer {

	private $img;

	public function loadFile(string $path) {
		$this->img = imagecreatefrompng($path);
		return $this->img;
	}

	public function outputImage(string $fileName) {
		imagepng($this->img, $fileName);
		imagedestroy($this->img);
	}

	public function getHandle() {
		return $this->img;
	}

	public function setHandle($img) {
		imagedestroy($this->img);
		$this->img = $img;
	}
}
