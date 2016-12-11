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

include_once "io/File.php";
include_once "io/FileSystem.php";
include_once "SessionState.php";

class Session {

	private $fileSystem;
	private $passwordFile;
	private $settings;
	private $state = SessionState::GUEST;
	private $userName = "";

	public function __construct(FileSystem $fileSystem, Settings $settings) {
		$this->fileSystem = $fileSystem;
		$this->settings = $settings;
		$this->passwordFile = new File($fileSystem->getRoot(), $this->settings->passwordFile);

		if (!$this->start()) return;

		if (!$this->validate($_SESSION["br-user"] ?? "", $_SESSION["br-hash"] ?? "")) {
			$this->clear();
			return;
		}

		$this->userName = $_SESSION["br-user"];
		$this->state = SessionState::LOGGED_IN;
	}

	public function authenticate(string $user, string $password): bool {
		if (empty($user) || empty($password)) return false;

		if (!$this->passwordFile->open()) return true;

		$token = $user.":{SHA}".base64_encode(sha1($password, true));

		while ($this->passwordFile->hasNext()) {
			$r = $this->passwordFile->readLine();
			if (strpos($r, $token) === 0) {
				if (!$this->start()) return false;

				$_SESSION["br-user"] = $user;
				$_SESSION["br-hash"] = $this->makeHash($user, $this->settings->salt);

				$this->userName = $user;
				$this->state = SessionState::LOGGED_IN;

				$this->passwordFile->close();
				return true;
			}
		}

		$this->state = SessionState::LOGIN_FAILED;

		$this->passwordFile->close();
		return false;
	}

	public function clear() {
		if (session_name() != $this->settings->sessionName) return;
		if (session_status() != PHP_SESSION_ACTIVE) return;

		$_SESSION = array();
		session_destroy();

		$this->userName = "";
		$this->state = SessionState::GUEST;
	}

	public function getState(): int {
		return $this->state;
	}

	public function isAuthAvailable(): bool {
		return $this->passwordFile->exists();
	}

	public function getLoggedInUser(): string {
		return $this->userName;
	}

	public function authorize(Request $request): bool {
		return true;
	}

	private function start(): bool {
		if ($this->settings->salt === null) return false;
		if (session_status() == PHP_SESSION_ACTIVE) return true;

		//Try to start the session only if headers have not been sent and session is not already started
		if (!headers_sent() && session_status() == PHP_SESSION_NONE) {
			session_name($this->settings->sessionName);
			session_start();
		}

		//No session exists, nothing to do
		return session_status() == PHP_SESSION_ACTIVE;
	}

	private function validate(string $user, string $hash): bool {
		if (empty($user) || empty($hash)) return false;

		return $this->makeHash($user, $this->settings->salt) == $hash;
	}

	private function makeHash(string $user, string $salt): string {
		return md5($user.$salt);
	}
}
