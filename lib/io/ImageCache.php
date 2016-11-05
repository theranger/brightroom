<?php

include_once "Folder.php";

class ImageCache extends Folder {

	public function __construct($path) {
		parent::__construct($path);
		if (!$this->exists()) $this->create(0775);
	}

	public function read(string $name): bool {
		$file = new File($this, $name);
		return $file->read();
	}

	public function save(string $name, resource $data): bool {
		$file = new File($this, $name);
		return $file->save($data);
	}

	public function inCache(string $name): bool {
		return (new File($this, $name))->exists();
	}

	public function getImagePath(string $name): string {
		return (new File($this, $name))->getPath();
	}

	public function invalidateCache(): bool {
		if (!$this->exists()) return true;
		return $this->remove();
	}

	public function invalidateImage(string $name): bool {
		$file = new File($this, $name);
		if (!$file->exists()) return true;
		return $file->remove();
	}
}
