<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class FileSystem {

	private $path;

	public function __construct(string $base, string $path) {
		$this->path = $base . "/" . $path;
	}

	public function createFolder(): Folder {
		if (!is_dir($this->path)) return null;
		return new Folder($this->path);
	}

	public function createFile(): File {
		if (!is_file($this->path)) return null;
		return new File(new Folder(dirname($this->path)), basename($this->path));
	}

	public function isDirectory(): bool {
		return is_dir($this->path);
	}

	public function isFile(): bool {
		return is_file($this->path);
	}
}
