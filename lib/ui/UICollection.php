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
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UICollection {

	private static $items;
	private static $folder;
	private static $settings;
	private static $session;

	/**
	 * UIFolder constructor.
	 * @param Session $session
	 * @param Settings $settings
	 * @param DirectoryEntry[] $items
	 * @param Folder $folder
	 */
	public function __construct(Session $session, Settings $settings, array &$items, Folder $folder) {
		self::$items = $items;
		self::$folder = $folder;
		self::$settings = $settings;
		self::$session = $session;
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
			if (!$item->isFile() || !self::IsAccessible($item)) continue;
			print '<a href="'.$item->getURL().'">';
			print '<img src="'.$item->getURL().'?thumbnail=true" alt="'.$item->getName().'" />';
			print '</a>';
		}
	}

	public static function PrintBadges() {
		foreach (self::$folder->getChildren() as &$item) {
			if (!$item->isDirectory()) continue;
			if ($item instanceof SecuredFolder && !self::$session->authorize($item)) continue;

			print '<a class="br-badge" href="'.$item->getURL().'" title="'.$item->getName().'">';
			print '<img src="'.$item->getURL().'" alt="'.$item->getName().'" />';
			print '</a>';
		}
	}

	public static function PrintFolders() {
		if (empty(self::$folder->getChildren())) return;

		print '<ul class="br-tree">';
		foreach (self::$folder->getChildren() as &$item) {
			if ($item->isFile()) continue;
			if ($item instanceof SecuredFolder && !self::$session->authorize($item)) continue;

			print '<li><a href="'.$item->getURL().'">'.$item->getName().'</a></li>';
		}
		print '</ul>';
	}

	public static function PrintFiles() {
		if (empty(self::$items)) return;

		print '<ul class="br-tree">';
		foreach (self::$items as &$item) {
			if (!$item->isFile() || !self::IsAccessible($item)) continue;
			print '<li><a href="'.$item->getURL().'">'.$item->getName().'</a></li>';
		}
		print '</ul>';
	}

	private static function IsAccessible(DirectoryEntry $directoryEntry): bool {
		switch($directoryEntry->getName()) {
			case self::$settings->accessFile:
			case self::$settings->passwordFile:
				return false;

			default:
				return true;
		}
	}
}
