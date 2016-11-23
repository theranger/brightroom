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
 * Created by The Ranger (ranger@risk.ee) on 2016-11-08
 *
 */
class UINavigation {

	private static $folders;
	private static $currentEntry;

	/**
	 * UINavigation constructor.
	 * @param Folder[] $folders
	 * @param DirectoryEntry $currentEntry
	 */
	public function __construct(array $folders, DirectoryEntry $currentEntry) {
		self::$folders = $folders;
		self::$currentEntry = $currentEntry;
	}

	public static function PrintTree() {
		if (empty(self::$folders)) return;
		self::doPrintTree(self::$folders);
	}

	/**
	 * @param Folder[] $folders
	 */
	private static function doPrintTree(array $folders) {
		if (empty($folders)) return;

		print '<ul class="br-tree">';
		foreach ($folders as &$folder) {
			if (self::$currentEntry->isEqual($folder)) print '<li class="selected">';
			elseif ($folder->isInPath()) print '<li class="opened">';
			else print '<li>';

			print '<a href="'.$folder->getURL().'">'.$folder->getName().'</a>';
			print '</li>';
			self::doPrintTree($folder->getChildren());
		}
		print '</ul>';
	}

	public static function PrintBreadcrumb() {
		self::doPrintBreadcrumb(self::$folders);

		if (!self::$currentEntry->isFile()) return;
		print '<a href="'.self::$currentEntry->getURL().'" class="br-breadcrumb-file">'.self::abbreviateName(self::$currentEntry->getName(), 30).'</a>';
	}

	/**
	 * @param Folder[] $folders
	 */
	private static function doPrintBreadcrumb(array $folders) {
		if (empty($folders)) return;

		foreach ($folders as $key => &$folder) {
			if (!$folder->isInPath()) continue;
			print '<a href="'.$folder->getURL().'" class="br-breadcrumb-folder">'.self::abbreviateName($folder->getName()).'</a>';
			self::doPrintBreadcrumb($folder->getChildren());
		}
	}

	private static function abbreviateName(string $name, $count = 20): string {
		if (strlen($name) < $count) return $name;
		$pos = strrpos($name, " ", $count - strlen($name));
		if ($pos === false) $pos = $count;

		return substr($name, 0, $pos)."...";
	}
}
