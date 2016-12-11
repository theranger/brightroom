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
 * Created by The Ranger (ranger@risk.ee) on 2016-11-08
 *
 */
class UINavigation {

	private static $entries;
	private static $currentEntry;
	private static $session;

	/**
	 * UINavigation constructor.
	 * @param Session $session
	 * @param DirectoryEntry[] $entries
	 * @param DirectoryEntry $currentEntry
	 */
	public function __construct(Session $session, array $entries, DirectoryEntry $currentEntry) {
		self::$entries = $entries;
		self::$currentEntry = $currentEntry;
		self::$session = $session;
	}

	public static function PrintTree() {
		if (empty(self::$entries)) return;
		self::doPrintTree(self::$entries);
	}

	/**
	 * @param DirectoryEntry[] $entries
	 */
	private static function doPrintTree(array $entries) {
		if (empty($entries)) return;

		print '<ul class="br-tree">';
		foreach ($entries as &$entry) {
			if (!$entry->isDirectory()) continue;
			if (!self::$session->authorize($entry)) continue;

			if (self::$currentEntry->isEqual($entry)) print '<li class="selected">';
			elseif ($entry->isInPath()) print '<li class="opened">';
			else print '<li>';

			print '<a href="'.$entry->getURL().'">'.$entry->getName().'</a>';
			print '</li>';
			self::doPrintTree($entry->getChildren());
		}
		print '</ul>';
	}

	public static function PrintBreadcrumb() {
		self::doPrintBreadcrumb(self::$entries);

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
