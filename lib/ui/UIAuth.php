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

include_once "IStaticModule.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class UIAuth implements IStaticModule {

	private static $session;

	public function __construct(Session $session) {
		self::$session = $session;
	}

	public static function isLoggedIn(): bool {
		return self::$session->isLoggedIn();
	}

	public static function PrintLogin() {
		self::isLoggedIn() ? print '<a class="br-auth" href="?logout">Log out</a>' : print '<a class="br-auth" href="?login">Log in</a>';
	}

	public static function PrintUserName() {
		if (!self::$session->isLoggedIn()) return;
		print '<a class="br-auth" href="?profile">'.self::$session->getLoggedInUser().'</a>';
	}

	public static function PrintLoginDialog() { ?>
		<form method="post" class="br-auth">
			<p><label for="username">Username:</label><input type="text" name="username" id="username" /></p>
			<p><label for="password">Password:</label><input type="password" name="password" id="password" /></p>
			<p><input type="submit" value="Log In" class="br-button" /></p>
		</form>
	<?php }

	function printContent() {
		if (!self::$session->isLoggedIn()) {
			self::PrintLoginDialog();
			return;
		}

		self::PrintUserName();
	}
}
