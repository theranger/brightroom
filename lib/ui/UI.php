<?php

include_once "UIAuth.php";
include_once "UIFolder.php";

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

	public static function PrintHeader() { ?>
		<title>Simple Folder Gallery</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="author" content="The Ranger (ranger.risk.ee)" />
		<meta name="description" content="My photo site provided by simple folder based gallery engine" />
		<meta name="generator" content="Simple Folder Gallery <?php self::$settings->version ?>" />
		<link rel="stylesheet" type="text/css" href="<?php self::PrintThemeUrl() ?>/style.css" />
	<?php }
}
