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

	private $userName;
	private $fileSystem;
	private $passwordFile;
	private $settings;
	private $cachedPath = array();
	private $state = SessionState::GUEST;

	public function __construct(FileSystem $fileSystem, Settings $settings) {
		$this->fileSystem = $fileSystem;
		$this->settings = $settings;
		$this->passwordFile = new File($fileSystem->getRoot(), $this->settings->passwordFile);
		$this->init();
	}

	public function authenticate(string $user, string $password): bool {
		if (empty($user) || empty($password)) return false;

		if (!$this->passwordFile->open()) return true;

		$token = $user.":{SHA}".base64_encode(sha1($password, true));

		while ($this->passwordFile->hasNext()) {
			$r = $this->passwordFile->readLine();
			if (strpos($r, $token) === 0) {
				if (!$this->isLoggedIn()) session_start();

				$this->userName = $user;
				$_SESSION["br-user"] = $user;
				$_SESSION["br-hash"] = $this->makeHash($this->userName, $this->settings->salt);

				$this->passwordFile->close();
				$this->state = SessionState::LOGGED_IN;
				return true;
			}
		}

		$this->passwordFile->close();
		$this->state = SessionState::LOGIN_FAILED;
		return false;
	}

	public function authorize(string $path): bool {
		if (empty($path)) return true;

		if (isset($this->cachedPath[$path])) {
			if ($this->cachedPath[$path]) return $this->authorize(substr($path, 0, strrpos($path, "/")));
			return false;
		}

		$accessFile = new File($this->fileSystem->getFolder(), $path."/".$this->settings->accessFile);
		if ($accessFile->open($path.'/'.$this->settings->accessFile) == false) {
			$this->cachedPath[$path] = true;
			return $this->authorize(substr($path, 0, strrpos($path, "/")));
		}

		while ($accessFile->hasNext()) {
			$r = $accessFile->readLine();
			if (strpos($r, $this->userName) === 0) {
				$this->cachedPath[$path] = true;
				return $this->authorize(substr($path, 0, strrpos($path, "/")));
			}
		}

		$this->cachedPath[$path] = false;
		return false;
	}

	public function clear() {
		if (!$this->isLoggedIn()) return;

		$this->userName = "";
		$_SESSION = array();

		session_destroy();
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

	public function isLoggedIn(): bool {
		return isset($_SESSION["br-hash"]);
	}

	private function init(): bool {
		if ($this->isLoggedIn()) return true;
		if ($this->settings->salt === null) return false;

		//Try to start the session only if headers have not been sent and session is not already started
		if (!headers_sent() && session_status() == PHP_SESSION_NONE) {
			session_name($this->settings->sessionName);
			session_start();
		}

		//No session exists, nothing to do
		if (session_status() != PHP_SESSION_ACTIVE) return false;

		if (isset($_SESSION["br-hash"]) && isset($_SESSION["br-user"])) {
			if ($this->makeHash($_SESSION["br-user"], $this->settings->salt) == $_SESSION["br-hash"]) {
				$this->userName = $_SESSION["br-user"];
				return true;
			}
		}

		$this->userName = "";

		//Destroy only if this is our session
		if (session_name() == $this->settings->sessionName) session_destroy();
		return false;
	}

	private function makeHash(string $user, string $salt): string {
		return md5($user.$salt);
	}
}
