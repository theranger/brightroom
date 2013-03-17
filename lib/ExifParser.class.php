<?php

class ExifParser {
	
	private $exifData;
	private $fileName;
	
	public function __construct($img) {
		$this->exifData = exif_read_data($img);
		$this->fileName = basename($img);
	}
	
	public function getFileSize() {
		if(!isset($this->exifData["FileSize"])) return;
		 
		return $this->exifData["FileSize"];
	}
	
	public function printFileSize() {
		echo $this->getFileSize();
	}
	
	public function getComment() {
		if(!isset($this->exifData["Comments"])) return;
		
		return $this->exifData["Comments"];
	}
	
	public function printComment() {
		echo $this->getComment();
	}
	
	public function getTitle() {
		if(!isset($this->exifData["Title"])) return $this->fileName;
		
		return $this->exifData["Title"];
	}
	
	public function printTitle() {
		echo $this->getTitle();
	}
	
	public function getDescription() {
		if(!isset($this->exifData["Subject"])) return;
		
		return $this->exifData["Subject"];
	}
	
	public function printDescription() {
		echo $this->getDescription();
	}
}