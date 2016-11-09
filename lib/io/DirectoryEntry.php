<?php
/**
 * Copyright 2016 The Ranger <ranger@risk.ee>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
		$this->url = "/".trim($url, "/");
		$this->base = $base;
		$this->path = $base[0] == "/" ? $base.$this->url : getcwd()."/".$base.$this->url;
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

		if ($this->type === false || empty($this->type)) return ContentType::OCTETSTREAM;
		return $this->type;
	}

	/**
	 * @return DirectoryEntry[]
	 */
	public function getChildren(): array {
		return $this->children;
	}
}
