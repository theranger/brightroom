<?php

class FileSystemHandler {

	private $dataPath;

	public function __construct($dataPath) {
		$this->dataPath = $dataPath;
	}


	public function getFilesArray($url) {
		$dh = opendir($this->dataPath.'/'.$url);
		if($dh == false) return;

		$files = array();
		while(($entry = readdir($dh)) !== false) {
			if($entry[0]=='.') continue;
			$files[] = array(
				"type"	=> is_dir($this->dataPath.'/'.$url.'/'.$entry)?"directory":"file",
				"name"	=> $entry,
			);
		}

		closedir($dh);
		return $files;
	}


	public function getFile($url) {
		if(empty($url)) return false;

		$fp = fopen($this->dataPath.'/'.$url, "rb");
		if($fp == false) return false;

		header("Content-Type:" . $this->getMimeType($url) ."\r\n");
		fpassthru($fp);

		fclose($fp);
		return true;
	}

	public function getMimeType($url) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo,$this->dataPath.'/'.$url);
		finfo_close($finfo);

		return $mime;
	}

	public function isDirectory($url) {
		return is_dir($this->dataPath.'/'.$url);
	}

	public function getFullPath($url) {
		return $this->dataPath.'/'.$url;
	}
	
	public function exists($url = "") {
		return is_readable($this->dataPath.'/'.$url);
	}
	
	public function createDirectory($url = "") {
		return mkdir($this->dataPath.'/'.$url);
	}
	
	public function saveFile($url, $data) {
		if(empty($url)) return false;
		
		$fp = fopen($this->dataPath.'/'.$url, "wb");
		if($fp == false) return false;
		
		$ret = fputs($fp, $data);
		fclose($fp);
		
		return $ret !== false;
	}
	
	public function clearPath($url) {
		return preg_replace('/\w+\/\.\.\//', '', $url);
	}
}


?>