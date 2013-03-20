<?php

class ImageJPEGRenderer implements ImageRenderer {

	private $img;

	public function loadFile($path) {
		$this->img = imagecreatefromjpeg($path);
		return $this->img;
	}

	public function outputImage($img) {
		if($img != NULL)
			header('Content-Type: image/jpeg');

		imagejpeg($this->img, $img);
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


?>