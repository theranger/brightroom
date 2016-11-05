<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class FileSystem {

	private $base;
	private $url;
	private $path;

	public function __construct(string $base, string $url) {
		$this->base = $base;
		$this->url = $url;
		$this->path = $base . "/" . $url;
	}

	public function createFolder(): Folder {
		if (!is_dir($this->path)) return null;
		return new Folder($this->base, $this->url);
	}

	public function createFile(): File {
		if (!is_file($this->path)) return null;
		return new File(new Folder($this->base, dirname($this->url)), basename($this->path));
	}

	public function isDirectory(): bool {
		return is_dir($this->path);
	}

	public function isFile(): bool {
		return is_file($this->path);
	}
}
