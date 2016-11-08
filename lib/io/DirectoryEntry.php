<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-06
 *
 */
abstract class DirectoryEntry {

	protected $base;
	protected $url;

	protected $path;
	protected $type;
	private $name;

	protected $children = array();
	protected $inPath = false;

	public function __construct(string $base, string $url) {
		$this->url = "/" . trim($url, "/");
		$this->base = $base;
		$this->path = $base[0] == "/" ? $base . $this->url : getcwd() . "/" . $base . $this->url;
		$this->name = basename($url);
	}

	public function getURL(): string {
		return $this->url;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getPath(): string {
		return $this->path;
	}

	public function isDirectory(): bool {
		return is_dir($this->path);
	}

	public function isFile(): bool {
		return is_file($this->path);
	}

	public function isInPath(): bool {
		return $this->inPath;
	}

	protected function getBase(): string {
		return $this->base;
	}

	public function getType(): string {
		if ($this->type !== null) return $this->type;

		$fileInfo = @finfo_open(FILEINFO_MIME_TYPE);
		$this->type = @finfo_file($fileInfo, $this->path);
		finfo_close($fileInfo);

		if ($this->type === FALSE || empty($this->type)) return ContentType::OCTETSTREAM;
		return $this->type;
	}

	/**
	 * @return DirectoryEntry[]
	 */
	public function getChildren(): array {
		return $this->children;
	}
}
