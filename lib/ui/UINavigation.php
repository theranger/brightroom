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
		self::PrintRecursively(self::$folders);
	}

	private static function PrintRecursively(array $folders) {
		if (empty($folders)) return;

		print '<ul class="sfg-tree">';
		foreach ($folders as &$folder) {
			print '<li><a href="' . $folder->getURL() . '">' . $folder->getName() . '</a></li>';
			self::PrintRecursively($folder->getChildren());
		}
		print '</ul>';
	}
}
