<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UICollection {

	private static $items;

	/**
	 * UIFolder constructor.
	 * @param DirectoryEntry[] $items
	 */
	public function __construct(array &$items) {
		self::$items = $items;
	}

	/**
	 * @return DirectoryEntry[]
	 */
	public static function getItems(): array {
		return self::$items;
	}

	public static function PrintThumbnails() {
		if (empty(self::$items)) return;

		foreach (self::$items as &$item) {
			if (!$item->isFile()) continue;
			print '<a href="' . $item->getURL() . '">';
			print '<img src="' . $item->getURL() . '?thumbnail=true" alt="' . $item->getName() . '" />';
			print '</a>';
		}
	}

	public static function PrintFolders() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach (self::$items as &$item) {
			if ($item->isFile()) continue;
			print '<li><a href="' . $item->getURL() . '">' . $item->getName() . '</a></li>';
		}
		print '</ul>';
	}

	public static function PrintFiles() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach (self::$items as &$item) {
			if ($item->isDirectory()) continue;
			print '<li><a href="' . $item->getURL() . '">' . $item->getName() . '</a></li>';
		}
		print '</ul>';
	}
}
