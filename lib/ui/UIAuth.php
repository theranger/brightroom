<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UIAuth {

	private static $session;

	public function __construct(Session $session) {
		self::$session = $session;
	}

	public static function isLoggedIn(): bool {
		return self::$session->isLoggedIn();
	}

	public static function PrintUserName() {
		if (!self::$session->isLoggedIn()) return;

		print '<form class="sfg-login">';
		print 'Logged in as '.self::$session->getLoggedInUser().'. ';
		print '<a href="?logout=true">Log out</a>';
		print '</form>';
		return;
	}

	public static function PrintLoginDialog() {
		print '<form method="post" class="sfg-login">';
		print 'U: <input type="text" name="user" />';
		print 'P: <input type="password" name="pass" />';
		print '<input type="submit" value="Log In" class="sfg-button" />';
		print '</form>';
	}
}
