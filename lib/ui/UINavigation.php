<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-08
 *
 */
class UINavigation {

	private static $folders;

	/**
	 * UINavigation constructor.
	 * @param Folder[] $folders
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

		print '<ul class="sfg-tree">';
		foreach ($folders as &$folder) {
			print '<li><a href="' . $folder->getURL() . '">' . $folder->getName() . '</a></li>';
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
			print '<a href="' . $folder->getURL() . '" class="sfg-breadcrumb">' . $folder->getName() . '</a>';
			self::doPrintBreadcrumb($folder->getChildren());
		}
	}
}
