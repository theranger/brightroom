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

declare(strict_types = 1);

include_once "Folder.php";

class ImageCache extends Folder {

	public function __construct(string $base, string $location) {
		parent::__construct($base, $location);
		if (!$this->exists()) $this->create(0775);
	}

	public function read(string $name): bool {
		$file = new File($this, $name);
		return $file->read();
	}

	public function save(string $name, string $data): bool {
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
