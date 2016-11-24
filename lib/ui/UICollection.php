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

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UICollection {

	private static $items;
	private static $folder;

	/**
	 * UIFolder constructor.
	 * @param DirectoryEntry[] $items
	 */
	public function __construct(array &$items, Folder $folder) {
		self::$items = $items;
		self::$folder = $folder;
	}

	/**
	 * @return DirectoryEntry[]
	 */
	public static function getItems(): array {
		return self::$items;
	}

	public static function getURL(): string {
		return self::$folder->getURL();
	}

	public static function PrintURL() {
		print self::$folder->getURL();
	}

	public static function PrintThumbnails() {
		if (empty(self::$items)) return;

		foreach (self::$items as &$item) {
			if (!$item->isFile()) continue;
			print '<a href="'.$item->getURL().'">';
			print '<img src="'.$item->getURL().'?thumbnail=true" alt="'.$item->getName().'" />';
			print '</a>';
		}
	}

	public static function PrintFolders() {
		if (empty(self::$items)) return;

		print '<ul class="br-tree">';
		foreach (self::$items as &$item) {
			if ($item->isFile()) continue;
			print '<li><a href="'.$item->getURL().'">'.$item->getName().'</a></li>';
		}
		print '</ul>';
	}

	public static function PrintFiles() {
		if (empty(self::$items)) return;

		print '<ul class="br-tree">';
		foreach (self::$items as &$item) {
			if ($item->isDirectory()) continue;
			print '<li><a href="'.$item->getURL().'">'.$item->getName().'</a></li>';
		}
		print '</ul>';
	}
}
