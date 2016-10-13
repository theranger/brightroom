<?php

class FileSystemHandler {

	private $dataPath;
	private $dirSize;

	private $cachedURL;
	private $cachedFiles = array();
	private $cachedCurrentFile;
	private $cachedCurrentIndex;

	private $cachedRoot;
	private $cachedFolders = array();

	public function __construct(string $dataPath) {
		if($dataPath[0]=='/')
			$this->dataPath = $dataPath;
		else
			$this->dataPath = dirname(__FILE__).'/../'.$dataPath;
	}


	public function getFilesArray(string $directory): array {
		//If we are accessing the same URL, show cached results
		if(count($this->cachedFiles) > 0 && $this->cachedURL == $directory) return $this->cachedFiles;

		$dh = opendir($this->dataPath.'/'.$directory);
		if($dh == false) return array();

		$this->cachedFiles = array();
		while(($entry = readdir($dh)) !== false) {
			if($entry[0]=='.') continue;

			$mime = $this->getMimeType($directory.'/'.$entry);
			$this->cachedFiles[] = array(
				"name"	=> $entry,
				"type"	=> strpos($mime,'/') === false?$mime:dirname($mime),
				"folder"	=> is_dir($this->dataPath.'/'.$directory.'/'.$entry),
			);
			$this->dirSize += filesize($this->dataPath.'/'.$directory.'/'.$entry);
		}

		closedir($dh);

		usort($this->cachedFiles,array($this,"sortDirectories"));

		$this->cachedURL = $directory;
		return $this->cachedFiles;
	}

	public function getFolderArray(string $directory, string $root = null): array {
		//If we are loading the same root, show cached result
		if(count($this->cachedFolders) > 0 && $this->cachedRoot == $directory) return $this->cachedFolders;

		$path = explode('/', $directory, 2);
		$workDir = rtrim($root.'/'.$path[0], '/');

		$dh = opendir($this->dataPath.$workDir);
		if($dh == false) return array();

		$folders = array();
		while(($entry = readdir($dh)) !== false) {
			if($entry[0]=='.') continue;
			if(!is_dir($this->dataPath.$workDir.'/'.$entry)) continue;

			$folders[] = array(
				"name"	=> $entry,
				"link"	=> $workDir.'/'.$entry,
				"items"	=> (count($path) > 1 && strpos($path[1], $entry) === 0)?$this->getFolderArray($path[1], $workDir):array("count" => 0),
			);
		}

		sort($folders);
		$folders["count"] = count($folders);
		closedir($dh);

		if($root == null) {
			$this->cachedFolders = $folders;
			$this->cachedRoot = $directory;
		}

		return $folders;
	}

	public function getIndexOf(string $directory, string $currentFile, int $index=0, bool $null = true, bool $folders = false): int {
		$pos = $this->getCurrentIndexOf($directory, $currentFile);
		if($pos === null) return -1;

		$items = $this->getFilesArray($directory);
		$k = count($items);

		if($pos + $index >= $k) return $null?null:$items[$k-1]["name"];

		if($pos + $index < 0) {
			if($null) return -1;
			return (!$folders && $items[0]["folder"])?$items[$pos]["name"]:$items[0]["name"];
		}

		return (!$folders && $items[$pos + $index]["folder"])?$items[$pos]["name"]:$items[$pos + $index]["name"];
	}

	private function getCurrentIndexOf(string $directory, string $currentFile): int {
		if($directory == null || empty($directory)) return -1;
		if($currentFile == null || empty($currentFile)) return -1;

		if($this->cachedCurrentFile == $currentFile) return $this->cachedCurrentIndex;

		$items = $this->getFilesArray($directory);
		$k = count($items);

		for($i=0; $i<$k; $i++) {
			if(($items[$i]["name"] != $currentFile)) continue;

			$this->cachedCurrentIndex = $i;
			return $i;
		}

		return -1;
	}

	private function sortDirectories(array $a, array $b): int {
		if($a["type"]==$b["type"])
			return strcasecmp($a["name"],$b["name"]);

		if($a["type"]=="directory") return -1;
		if($b["type"]=="directory") return 1;

		return 0;
	}

	public function readFile(string $url): string {
		if(empty($url)) return "";
		if(!$this->exists($url)) return "";

		return file_get_contents($this->dataPath.'/'.$url);
	}

	public function getFile(string $url): bool {
		if(empty($url)) return false;

		$fp = fopen($this->dataPath.'/'.$url, "rb");
		if($fp == false) return false;

		fpassthru($fp);
		fclose($fp);
		return true;
	}

	public function getMimeType(string $url): string {
		$fileInfo = @finfo_open(FILEINFO_MIME_TYPE);
		$mime = @finfo_file($fileInfo,$this->dataPath.'/'.$url);
		finfo_close($fileInfo);

		if($mime === FALSE || empty($mime))
			$mime = "application/octet-stream";

		return $mime;
	}

	public function isDirectory(string $url): bool {
		return is_dir($this->dataPath.'/'.$url);
	}

	public function getFullPath(string $url): string {
		return $this->dataPath.'/'.$url;
	}

	public function exists(string $url = ""): bool {
		return is_readable($this->dataPath.'/'.$url);
	}

	public function createDirectory(string $url = "", int $perms = 0): bool {
		if(!mkdir($this->dataPath.'/'.$url)) return false;

		if($perms == 0) return true;
		return chmod($this->dataPath.'/'.$url, $perms);
	}

	public function removeDirectory(string $url = ""): bool {
		$dh = opendir($this->dataPath.'/'.$url);
		if($dh == false) return false;

		while(($entry = readdir($dh)) !== false) {
			if($entry=='.' || $entry == '..') continue;
			if(is_dir($this->dataPath.'/'.$url.'/'.$entry)) {
				$this->removeDirectory($url.'/'.$entry);
				continue;
			}

			if(!unlink($this->dataPath.'/'.$url.'/'.$entry)) {
				closedir($dh);
				return false;
			}
		}

		closedir($dh);
		return rmdir($this->dataPath.'/'.$url);
	}

	public function removeFile(string $path): bool {
		if(empty($path)) return false;

		$files = glob($this->dataPath.'/'.$path);
		foreach($files as $file) {
			if(!unlink($file)) return false;
		}

		return true;
	}

	public function saveFile(string $url, string $data): bool {
		if(empty($url)) return false;

		$fp = fopen($this->dataPath.'/'.$url, "wb");
		if($fp == false) return false;

		$ret = fputs($fp, $data);
		fclose($fp);

		return $ret !== false;
	}

	public function getDirectorySize(string $url): int {
		$this->getFilesArray($url);
		return $this->dirSize;
	}

	public function getDirectorySizeHuman(string $url): string {
		$this->getFilesArray($url);
		$size = $this->dirSize;

		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		for($i = 1; $i<count($units); $i++) {
			$size /= 1024;
			if($size <= 1024) break;
		}

		return round($size,2).' '.$units[$i];
	}

	public function getFileCount(string $url): int {
		$this->getFilesArray($url);
		return count($this->cachedFiles);
	}
}
