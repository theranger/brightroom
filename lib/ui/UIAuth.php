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
