<?php

class ExifParser {

	private $exifData;
	private $fileName;

	public function __construct(string $imagePath) {
		$this->exifData = exif_read_data($imagePath);
		$this->fileName = basename($imagePath);
	}

	public function getFileSize(): int {
		if(!isset($this->exifData["FileSize"])) return -1;

		return $this->exifData["FileSize"];
	}

	public function printFileSize() {
		echo $this->getFileSize();
	}

	public function getOrientation(): int {
		if(!key_exists("Orientation", $this->exifData)) return 0;

		switch($this->exifData["Orientation"]) {
			case 6:
				return -90;
			case 8:
				return 90;
			default:
				return 0;
		}
	}

	public function getComment(): string {
		if(!isset($this->exifData["Comments"])) return "";

		return $this->exifData["Comments"];
	}

	public function printComment() {
		echo $this->getComment();
	}

	public function getTitle(): string {
		if(!isset($this->exifData["Title"])) return $this->fileName;

		return $this->exifData["Title"];
	}

	public function printTitle() {
		echo $this->getTitle();
	}

	public function getDescription(): string {
		if(isset($this->exifData["ImageDescription"])) return $this->exifData["ImageDescription"];
		if(isset($this->exifData["UserComment"])) return $this->exifData["UserComment"];
		return "";
	}

	public function printDescription() {
		echo $this->getDescription();
	}
}
