<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-06
 *
 */
class DirectoryEntry {

	protected $base;
	protected $url;

	protected $path;
	protected $type;
	private $name;

	public function __construct(string $base, string $location) {
		$this->url = $location;
		$this->base = $base;
		$this->path = $base[0] == "/" ? $base . "/" . $location : getcwd() . "/" . $base . "/" . $location;
		$this->name = basename($location);
	}

	public function getURL(): string {
		return $this->url;
	}

	public function getName(): string {
		return $this->name;
	}

	public function isDirectory(): bool {
		return is_dir($this->path);
	}

	public function isFile(): bool {
		return is_file($this->path);
	}

	public function getType(): string {
		if ($this->type !== null) return $this->type;

		$fileInfo = @finfo_open(FILEINFO_MIME_TYPE);
		$this->type = @finfo_file($fileInfo, $this->path);
		finfo_close($fileInfo);

		if ($this->type === FALSE || empty($this->type)) return ContentType::OCTETSTREAM;
		return $this->type;
	}
}
