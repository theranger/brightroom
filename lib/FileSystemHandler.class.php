<?php

class FileSystemHandler {

	private $dataPath;
	private $dirSize;
	
	private $cachedURL;
	private $cachedFiles = array();

	public function __construct($dataPath) {
		if($dataPath[0]=='/')
			$this->dataPath = $dataPath;
		else
			$this->dataPath = dirname(__FILE__).'/../'.$dataPath;
	}


	public function getFilesArray($url) {
		//If we are accessing the same URL, show cached results
		if(count($this->cachedFiles) > 0 && $this->cachedURL == $url) return $this->cachedFiles;
		
		$dh = opendir($this->dataPath.'/'.$url);
		if($dh == false) return;

		$this->cachedFiles = array();
		while(($entry = readdir($dh)) !== false) {
			if($entry[0]=='.') continue;
			$mime = $this->getMimeType($url.'/'.$entry);
			$this->cachedFiles[] = array(
				"name"	=> $entry,
				"type"	=> strpos($mime,'/') === false?$mime:dirname($mime),
			);
			$this->dirSize += filesize($this->dataPath.'/'.$url.'/'.$entry);
		}

		closedir($dh);
		
		usort($this->cachedFiles,array($this,"sortDirectories"));
		
		$this->cachedURL = $url;
		return $this->cachedFiles;
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
	
	public function getDirectorySize($url) {
		$this->getFilesArray($url);
		return $this->dirSize;
	}
	
	public function getDriectorySizeHuman($url) {
		$this->getFilesArray($url);
		$size = $this->dirSize;
		
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
	
		for($i = 1; $i<count($units); $i++) {
			$size /= 1024;
			if($size <= 1024) break;
		}
		
		return round($size,2).' '.$units[$i];
	}
	
	public function getFileCount($url) {
		$this->getFilesArray($url);
		return count($this->cachedFiles);
	}
}


?>