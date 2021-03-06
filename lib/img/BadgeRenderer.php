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

include_once "system/Settings.php";
include_once "io/Folder.php";
include_once "ImageRenderer.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-12-13
 *
 */
class BadgeRenderer extends ImageRenderer {

	private $folderBadge;

	public function __construct(Settings $settings, Folder $folder, File $defaultBadge) {
		$this->folderBadge = $this->getBadge($folder);
		parent::__construct($settings, $this->folderBadge === NULL ? $defaultBadge : $this->folderBadge);
	}

	private function getBadge(Folder $folder) {
		$entries = $folder->getContents();
		foreach ($entries as $entry) {
			if ($entry instanceof File) return $entry;
		}

		foreach ($entries as $entry) {
			if (!($entry instanceof Folder)) continue;
			if (($file = $this->getBadge($entry)) !== NULL) return $file;
		}

		return NULL;
	}

	public function render(int $size = 0, int $orientation = 0, bool $disableCache = false): bool {
		return parent::render($size, $orientation, $this->folderBadge === NULL);
	}
}
