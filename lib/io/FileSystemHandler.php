<?php

class FileSystemHandler {

	private $dataPath;
	private $cachedURL;
	private $cachedContents = array();

	public function __construct(string $dataPath) {
		if($dataPath[0]=='/')
			$this->dataPath = $dataPath;
		else
			$this->dataPath = dirname(__FILE__).'/../../'.$dataPath;
	}

	public function getContents(string $directory): array {
		//If we are accessing the same URL, show cached results
		if(count($this->cachedContents) > 0 && $this->cachedURL == $directory) return $this->cachedContents;

		$dh = opendir($this->dataPath.'/'.$directory);
		if($dh == false) return array();

		while(($entry = readdir($dh)) !== false) {
			if($entry[0]=='.') continue;

			$mime = $this->getMimeType($directory.'/'.$entry);
			$this->cachedContents[] = array(
				"name"		=> $entry,
				"type"		=> strpos($mime,'/') === false?$mime:dirname($mime),
				"folder"	=> $this->isDirectory($directory.'/'.$entry),
				"link"		=> $directory.'/'.$entry,
			);
		}

		closedir($dh);

		usort($this->cachedContents,array($this,"sortDirectories"));
		return $this->cachedContents;
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
}
