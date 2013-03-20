<?php

class FileSystemHandler {

	private $dataPath;
	private $dirSize;

	private $cachedURL;
	private $cachedFiles = array();
	private $cachedCurrentFile;
	private $cachedCurrentIndex;

	public function __construct($dataPath) {
		if($dataPath[0]=='/')
			$this->dataPath = $dataPath;
		else
			$this->dataPath = dirname(__FILE__).'/../'.$dataPath;
	}


	public function getFilesArray($directory) {
		//If we are accessing the same URL, show cached results
		if(count($this->cachedFiles) > 0 && $this->cachedURL == $directory) return $this->cachedFiles;

		$dh = opendir($this->dataPath.'/'.$directory);
		if($dh == false) return;

		$this->cachedFiles = array();
		while(($entry = readdir($dh)) !== false) {
			if($entry[0]=='.') continue;
			$mime = $this->getMimeType($directory.'/'.$entry);
			$this->cachedFiles[] = array(
				"name"	=> $entry,
				"type"	=> strpos($mime,'/') === false?$mime:dirname($mime),
			);
			$this->dirSize += filesize($this->dataPath.'/'.$directory.'/'.$entry);
		}

		closedir($dh);

		usort($this->cachedFiles,array($this,"sortDirectories"));

		$this->cachedURL = $directory;
		return $this->cachedFiles;
	}

	public function getIndexOf($directory, $currentFile, $index=0, $null = true) {
		$pos = $this->getCurrentIndexOf($directory, $currentFile);
		if($pos == null) return null;

		$items = $this->getFilesArray($directory);
		$k = count($items);

		if($pos + $index >= $k) return $null?null:$items[$k-1]["name"];
		if($pos + $index < 0) return $null?null:$items[0]["name"];

		return $items[$pos + $index]["name"];
	}

	private function getCurrentIndexOf($directory, $currentFile) {
		if($directory == null || empty($directory)) return null;
		if($currentFile == null || empty($currentFile)) return null;

		if($this->cachedCurrentFile == $currentFile) return $this->cachedCurrentIndex;

		$items = $this->getFilesArray($directory);
		$k = count($items);

		for($i=0; $i<$k; $i++) {
			if(($items[$i]["name"] != $currentFile)) continue;

			$this->cachedCurrentIndex = $i;
			return $i;
		}

		return null;
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