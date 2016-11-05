<?php

class File {

	private $name;
	private $folder;
	private $path;
	private $fh = null;
	private $type = null;

	public function __construct(Folder $folder, string $name) {
		$this->folder = $folder;
		$this->name = $name;
		$this->path = $folder->getPath() . "/" . $name;
	}

	public function getPath(): string {
		return $this->path;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getFolder(): Folder {
		return $this->folder;
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

	public function getType(): string {
		if ($this->type !== null) return $this->type;

		$fileInfo = @finfo_open(FILEINFO_MIME_TYPE);
		$this->type = @finfo_file($fileInfo, $this->path);
		finfo_close($fileInfo);

		if ($this->type === FALSE || empty($this->type)) return ContentType::OCTETSTREAM;
		return $this->type;
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
