<?php

class ExifParser {
	
	private $exifData;
	
	public function __construct($img) {
		$this->exifData = exif_read_data($img);
	}
	
	public function getFileSize() {
		if(!isset($this->exifData["FileSize"])) return;
		 
		return $this->exifData["FileSize"];
	}
	
	public function getComment() {
		if(!isset($this->exifData["Comments"])) return;
		
		return $this->exifData["Comments"];
	}
	
	public function getTitle() {
		if(!isset($this->exifData["Title"])) return;
		
		return $this->exifData["Title"];
	}
	
	public function getDescription() {
		if(!isset($this->exifData["Subject"])) return;
		
		return $this->exifData["Subject"];
	}
}