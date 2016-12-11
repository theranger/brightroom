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

include_once "FileSystem.php";
include_once "SecuredFolder.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-12-11
 *
 */
class SecuredFileSystem extends FileSystem {

	private $settings;

	public function __construct(string $base, string $url, Settings $settings) {
		$this->settings = $settings;
		parent::__construct($base, $url);
	}

	protected function initialize(string $base, string $url, string $path) {
		if (is_dir($path)) {
			$this->folder = new SecuredFolder($base, $url, $this->settings);
			$this->root = $this->listFolders($this->folder);
			$this->entryType = EntryType::FOLDER;
			$this->aggregatePermissions($this->root);
			return;
		}

		if (is_file($path)) {
			$this->folder = new SecuredFolder($base, dirname($url), $this->settings);
			$this->root = $this->listFolders($this->folder);
			$this->file = new File($this->folder, basename($url));
			$this->entryType = EntryType::FILE;
			$this->aggregatePermissions($this->root);
			return;
		}
	}

	private function listFolders(SecuredFolder $folder): SecuredFolder {
		try {
			$folders = $this->listFolders($folder->parentSecuredFolder())->getFolders();
			foreach ($folders as $key => $f) {
				if (!$f->isEqual($folder)) continue;
				if ($folder->getURL() != $this->folder->getURL()) return $f;

				// Found current leaf. Point current folder to this entry and fill sub-folder list.
				$this->folder = &$f;
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

	/**
	 * @return SecuredFolder
	 * @throws IOException
	 */
	public function getSecuredFolder(): SecuredFolder {
		if (!isset($this->folder)) throw new IOException("Folder does not exist or is not readable");

		return $this->folder;
	}

	public function getSecuredRoot(): SecuredFolder {
		return $this->root;
	}

	private function aggregatePermissions(SecuredFolder $folder) {
		foreach ($folder->getChildren() as $directoryEntry) {
			if ($directoryEntry instanceof SecuredFolder) {
				$directoryEntry->aggregateACL($folder);
				$this->aggregatePermissions($directoryEntry);
			}
		}
	}
}
