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

	/**
	 * UINavigation constructor.
	 * @param Folder[] $folders
	 * @param Folder $currentFolder
	 */
	public function __construct(array $folders) {
		self::$folders = $folders;
	}

	public static function PrintTree() {
		if (empty(self::$folders)) return;
		self::doPrintTree(self::$folders);
	}

	private static function doPrintTree(array $folders) {
		if (empty($folders)) return;

		print '<ul class="br-tree">';
		foreach ($folders as &$folder) {
			print '<li'.($folder->isInPath()?' class="selected"':'').'><a href="'.$folder->getURL().'">'.$folder->getName().'</a></li>';
			self::doPrintTree($folder->getChildren());
		}
		print '</ul>';
	}

	public static function PrintBreadcrumb() {
		if (empty(self::$folders)) return;
		self::doPrintBreadcrumb(self::$folders);
	}

	/**
	 * @param Folder[] $folders
	 */
	private static function doPrintBreadcrumb(array $folders) {
		if (empty($folders)) return;

		foreach ($folders as &$folder) {
			if (!$folder->isInPath()) continue;
			print '<a href="'.$folder->getURL().'" class="br-breadcrumb">'.$folder->getName().'</a>';
			self::doPrintBreadcrumb($folder->getChildren());
		}
	}
}
