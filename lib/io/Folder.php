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
include_once "IOException.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class Folder extends DirectoryEntry {

	private $cachedContents = array();

	public function getPath(): string {
		return $this->path;
	}

	public function exists(): bool {
		return is_dir($this->path);
	}

	/**
	 * @return DirectoryEntry[]
	 */
	public function getContents(): array {
		//If we are accessing the same URL, show cached results
		if (!empty($this->cachedContents)) return $this->cachedContents;

		$dh = opendir($this->path);
		if ($dh == false) return array();

		while (($entry = readdir($dh)) !== false) {
			if ($entry[0] == '.') continue;

			if (is_dir($this->path."/".$entry)) {
				$this->cachedContents[] = new Folder($this->base, $this->url."/".$entry);
				continue;
			}

			if (is_file($this->path."/".$entry)) {
				$this->cachedContents[] = new File($this, $entry);
				continue;
			}
		}

		closedir($dh);

		usort($this->cachedContents, array($this, "sortDirectories"));
		return $this->cachedContents;
	}

	/**
	 * @return array|Folder[]
	 * @throws SystemException
	 */
	public function getFolders(): array {
		if (!empty($this->children)) return $this->children;

		$dh = opendir($this->path);
		if ($dh == false) return array();
		if (!method_exists("Normalizer", "normalize")) throw new SystemException("PHP 'intl' module not installed");

		while (($entry = readdir($dh)) !== false) {
			if ($entry[0] == '.') continue;

			if (!is_dir($this->path."/".$entry)) continue;
			$this->children[] = new Folder($this->base, $this->url."/".Normalizer::normalize($entry));
		}

		closedir($dh);
		return $this->children;
	}

	public function isEqual(DirectoryEntry $entry): bool {
		if (!$entry->isDirectory()) return false;
		$this->inPath = $this->path == $entry->path;
		return $this->inPath;
	}

	/**
	 * @return Folder
	 * @throws IOException
	 */
	public function parentFolder(): Folder {
		if (empty($this->url) || $this->url == "/") throw new IOException("Parent folder not found");
		return new Folder($this->base, dirname($this->url));
	}

	public function create(int $perms = 0): bool {
		if (!mkdir($this->path)) return false;

		if ($perms == 0) return true;
		return chmod($this->path, $perms);
	}

	public function remove(): bool {
		$dh = opendir($this->path);
		if ($dh == false) return false;

		while (($entry = readdir($dh)) !== false) {
			if ($entry == '.' || $entry == '..') continue;
			if (is_dir($this->path."/".$entry)) {
				(new Folder($this->path, $entry))->remove();
				continue;
			}

			if (!unlink($this->path."/".$entry)) {
				closedir($dh);
				return false;
			}
		}

		closedir($dh);
		return rmdir($this->path);
	}

	private function sortDirectories(DirectoryEntry $a, DirectoryEntry $b): int {
		if ($a->isDirectory() && $b->isDirectory()) return strcasecmp($a->getName(), $b->getName());
		if ($a->isDirectory()) return -1;
		if ($b->isDirectory()) return 1;

		return 0;
	}
}
