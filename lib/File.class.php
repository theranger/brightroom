<?php

class File {

	private $fileSystemHandler;
	private $fh = false;

	public function __construct($fileSystemHandler) {
		$this->fileSystemHandler = $fileSystemHandler;
	}

	public function open($path, $mode="r") {
		if($this->fh != false) $this->close();

		$this->fh = @fopen($this->fileSystemHandler->getFullPath($path), $mode);
		return $this->fh !== false;
	}

	public function readLine() {
		return fgets($this->fh);
	}

	public function hasNext() {
		if($this->fh == false) return false;
		return !feof($this->fh);
	}

	public function close() {
		if($this->fh == false) return;

		fclose($this->fh);
		$this->fh = false;
	}
}