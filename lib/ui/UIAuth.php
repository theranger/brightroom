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
		return self::$session->getState() == SessionState::LOGGED_IN;
	}

	public static function PrintLogin() {
		if (!self::$session->isAuthAvailable()) return;

		if (self::$session->getState() == SessionState::LOGGED_IN) {
			print '<p class="br-auth">Welcome, ' . self::$session->getLoggedInUser() . '!</p>';
			print '<a class="br-auth" href="?logout">Log out</a>';
			return;
		}

		print '<a class="br-auth" href="?login">Log in</a>';
	}

	public static function PrintUserName() {
		if (self::$session->getState() != SessionState::LOGGED_IN) return;
		print '<a class="br-auth" href="?profile">'.self::$session->getLoggedInUser().'</a>';
	}

	public static function PrintLoginDialog() { ?>
		<form method="post" class="br-auth">
			<p><?php if(self::$session->getState() == SessionState::LOGIN_FAILED): ?>Login failed<?php endif; ?></p>
			<p><label for="br-username">Username:</label><input type="text" name="br-username" id="br-username" /></p>
			<p><label for="br-password">Password:</label><input type="password" name="br-password" id="br-password" /></p>
			<p><input type="submit" value="Log In" class="br-button" /></p>
		</form>
	<?php }

	function printContent() {
		if (self::$session->getState() == SessionState::LOGGED_IN) {
			self::PrintUserName();
			return;
		}

		self::PrintLoginDialog();
	}
}
