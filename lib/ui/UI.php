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

include_once "UIAuth.php";
include_once "UICollection.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UI {

	private static $settings;

	public function __construct(Settings $settings, Session $session) {
		self::$settings = $settings;
		new UIAuth($session);
	}

	public static function Auth(): UIAuth {
		return UIAuth::class;
	}

	public static function Folder(): Collection {
		return Collection::class;
	}

	public static function PrintThemeUrl() {
		print self::$settings->getThemePrefix().'/'.self::$settings->theme;
	}

	public static function PrintHeader(string $title = "BrightRoom Gallery") { ?>
		<title><?php echo $title; ?></title>
		<meta charset="UTF-8">
		<meta name="author" content="The Ranger (ranger.risk.ee)">
		<meta name="description" content="Photo site served by BrightRoom PHP gallery">
		<meta name="generator" content="BrightRoom PHP Gallery <?php self::$settings->version ?>">
		<link rel="stylesheet" type="text/css" href="<?php self::PrintThemeUrl() ?>/style.css">
	<?php }
}
