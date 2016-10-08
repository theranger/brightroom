<?php

class File {

	private $fileSystemHandler;
	private $fh;

	public function __construct(FileSystemHandler $fileSystemHandler) {
		$this->fileSystemHandler = $fileSystemHandler;
		$this->fh = null;
	}

	public function open($path, $mode="r"): bool {
		if($this->fh != null) $this->close();

		$ret = @fopen($this->fileSystemHandler->getFullPath($path), $mode);
		if ($ret == false) return false;

		$this->fh = $ret;
		return true;
	}

	public function readLine(): string {
		if($this->fh == null) return "";
		return fgets($this->fh);
	}

	public function hasNext(): bool {
		if($this->fh == null) return false;
		return !feof($this->fh);
	}

	public function close() {
		if($this->fh == null) return;

		fclose($this->fh);
		$this->fh = null;
	}
}
