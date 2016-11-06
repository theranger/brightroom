<?php

include_once "DirectoryEntry.php";

class File extends DirectoryEntry {

	private $folder;
	private $fh = null;

	public function __construct(Folder $folder, string $name) {
		parent::__construct($folder->getBase(), $folder->getURL() . "/" . $name);
		$this->folder = $folder;
	}

	public function getPath(): string {
		return $this->path;
	}

	public function getFolder(): Folder {
		return $this->folder;
	}

	public function exists(): bool {
		return is_file($this->path);
	}

	public function open($mode = "r"): bool {
		if ($this->fh != null) $this->close();

		$ret = @fopen($this->path, $mode);
		if ($ret == false) return false;

		$this->fh = $ret;
		return true;
	}

	public function readLine(): string {
		if ($this->fh == null) return "";
		return fgets($this->fh);
	}

	public function hasNext(): bool {
		if ($this->fh == null) return false;
		return !feof($this->fh);
	}

	public function close() {
		if ($this->fh == null) return;

		fclose($this->fh);
		$this->fh = null;
	}

	public function remove(): bool {
		if (empty($this->path)) return false;

		$this->close();
		return unlink($this->path);
	}

	public function save(string $data): bool {
		if (empty($this->path)) return false;

		$fp = fopen($this->path, "wb");
		if ($fp == false) return false;

		$ret = fputs($fp, $data);
		fclose($fp);

		return $ret !== false;
	}

	public function load(): string {
		if (empty($this->path)) return "";
		if (!is_readable($this->path)) return "";
		return file_get_contents($this->path);
	}

	public function read(): bool {
		if (empty($this->path)) return false;

		$fp = fopen($this->path, "rb");
		if ($fp == false) return false;

		fpassthru($fp);
		fclose($fp);
		return true;
	}
}
