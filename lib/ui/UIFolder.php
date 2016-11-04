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

	public static function PrintTree(array &$items = null) {
		$level = "";

		if($items == null) {
			$items = self::$items;
			$level = "root";
		}

		if($items == null || $items["count"] == 0) return;

		print '<ul class="sfg-foldertree '.$level.'">';
		for($i = 0; $i < $items["count"]; $i++) {
			//if(!$this->session->authorize($items[$i]["link"])) continue;
			if(defined("VETO_FOLDERS") && strpos(VETO_FOLDERS, '/'.$items[$i]["name"].'/') !== false) continue;

			print '<li>';
			print '<a href="'.$items[$i]["link"].'">'.$items[$i]["name"].'</a>';
			self::PrintTree($items[$i]["items"]);
			print '</li>';
		}
		print '</ul>';
	}
}
