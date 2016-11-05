<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UIFolder {

	private static $items;

	public function __construct(array &$items) {
		self::$items = $items;
	}

	public static function PrintTree() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach(self::$items as &$item) {
			print '<li><a href="'.$item["link"].'">'.$item["name"].'</a></li>';
		}
		print '</ul>';
	}

	public static function PrintFolders() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach(self::$items as &$item) {
			if(!$item["folder"]) continue;
			print '<li><a href="'.$item["link"].'">'.$item["name"].'</a></li>';
		}
		print '</ul>';
	}

	public static function PrintFiles() {
		if (empty(self::$items)) return;

		print '<ul class="sfg-tree">';
		foreach(self::$items as &$item) {
			if($item["folder"]) continue;
			print '<li><a href="'.$item["link"].'">'.$item["name"].'</a></li>';
		}
		print '</ul>';
	}
}
