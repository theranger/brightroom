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

include_once "Folder.php";
include_once "File.php";
include_once "EntryType.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class FileSystem {

	private $folder;
	private $root;
	private $file;
	private $entryType = EntryType::UNKNOWN;

	public function __construct(string $base, string $url) {
		$path = $base."/".trim($url, "/");

		if (is_dir($path)) {
			$this->folder = new Folder($base, $url);
			$this->root = $this->listFolders($this->folder);
			$this->entryType = EntryType::FOLDER;
			return;
		}

		if (is_file($path)) {
			$this->folder = new Folder($base, dirname($url));
			$this->root = $this->listFolders($this->folder);
			$this->file = new File($this->folder, basename($url));
			$this->entryType = EntryType::FILE;
			return;
		}
	}

	public function getFolder(): Folder {
		return $this->folder;
	}

	public function getRoot(): Folder {
		return $this->root;
	}

	public function getFile(): File {
		return $this->file;
	}

	public function getEntryType(): int {
		return $this->entryType;
	}

	/**
	 * @param Folder $folder
	 * @return Folder
	 */
	private function listFolders(Folder $folder): Folder {
		try {
			$folders = $this->listFolders($folder->parentFolder())->getFolders();
			foreach ($folders as $key => $f) {
				if (!$f->isEqual($folder)) continue;
				if ($folder->getURL() != $this->folder->getURL()) return $f;
				$f->getFolders();
				return $this->root;
			}

			return $folder; // This should never happen
		}
		catch (IOException $ex) {
			$this->root = $folder;
			if ($folder->isEqual($this->folder)) $folder->getFolders();        // Gallery root was requested
			return $folder;
		}
	}
}
