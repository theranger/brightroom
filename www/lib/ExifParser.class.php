<?php

class ExifParser {
	
	private $exifData;
	
	public function __construct($img) {
		$this->exifData = exif_read_data($img);
	}
	
	public function getFileSize() {
		return $this->exifData["FileSize"];
	}
	
	public function getComment() {
		return $this->exifData["Comments"];
	}
	
	public function getTitle() {
		return $this->exifData["Title"];
	}
	
	public function getDescription() {
		return $this->exifData["Subject"];
	}
}