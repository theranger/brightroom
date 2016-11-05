<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UIFolder {

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

	public static function PrintTree() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach(self::$items as &$item) {
			print '<li><a href="'.$item->getURL().'">'.$item->getName().'</a></li>';
		}
		print '</ul>';
	}

	public static function PrintFolders() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach(self::$items as &$item) {
			if(!$item->getType() == "directory") continue;
			print '<li><a href="'.$item->getURL().'">'.$item->getName().'</a></li>';
		}
		print '</ul>';
	}

	public static function PrintFiles() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach(self::$items as &$item) {
			if($item->getType() == "directory") continue;
			print '<li><a href="'.$item->getURL().'">'.$item->getName().'</a></li>';
		}
		print '</ul>';
	}
}
