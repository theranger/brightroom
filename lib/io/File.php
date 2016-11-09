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

include_once "DirectoryEntry.php";

class File extends DirectoryEntry {

	private $folder;
	private $fh = null;

	public function __construct(Folder $folder, string $name) {
		parent::__construct($folder->getBase(), $folder->getURL()."/".$name);
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
