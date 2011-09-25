<?php

class FileSystemHandler {

	private $dataPath;

	public function __construct($dataPath) {
		if($dataPath[0]=='/')
			$this->dataPath = $dataPath;
		else
			$this->dataPath = dirname(__FILE__).'/../'.$dataPath;
	}


	public function getFilesArray($url) {
		$dh = opendir($this->dataPath.'/'.$url);
		if($dh == false) return;

		$files = array();
		while(($entry = readdir($dh)) !== false) {
			if($entry[0]=='.') continue;
			$mime = $this->getMimeType($url.'/'.$entry);
			$files[] = array(
				"name"	=> $entry,
				"type"	=> strpos($mime,'/') === false?$mime:dirname($mime),
			);
		}

		closedir($dh);
		
		usort($files,array($this,"sortDirectories"));
		
		return $files;
	}
	
	private function sortDirectories($a, $b) {
		if($a["type"]==$b["type"])
			return strcasecmp($a["name"],$b["name"]);
		
		if($a["type"]=="directory") return -1;
		if($b["type"]=="directory") return 1;

		return 0;
	}

	public function readFile($url) {
		if(empty($url)) return false;
		if(!$this->exists($url)) return false;
		
		return file_get_contents($this->dataPath.'/'.$url);
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
		$finfo = @finfo_open(FILEINFO_MIME_TYPE);
		$mime = @finfo_file($finfo,$this->dataPath.'/'.$url);
		finfo_close($finfo);
		
		if($mime === FALSE || empty($mime))
			$mime = "application/octet-stream";

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
	
	public function createDirectory($url = "", $perms = NULL) {
		if(!mkdir($this->dataPath.'/'.$url)) return false;
		
		if($perms != NULL) {
			chmod($this->dataPath.'/'.$url, $perms); 
		}
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