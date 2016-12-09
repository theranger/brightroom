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

class Session {

	private $userName;
	private $folder;
	private $settings;
	private $cachedPath = array();

	public function __construct(FileSystem $fileSystem, Settings $settings) {
		$this->folder = $fileSystem->getFolder();
		$this->settings = $settings;
		$this->init();
	}

	public function authenticate(string $user, string $password): bool {
		if (empty($user) || empty($password)) return false;

		$passwordFile = new File($this->folder, $this->settings->passwordFile);
		if (!$passwordFile->open()) return true;

		$token = $user.":{SHA}".base64_encode(sha1($password, true));

		while ($passwordFile->hasNext()) {
			$r = $passwordFile->readLine();
			if (strpos($r, $token) === 0) {
				if (!$this->isLoggedIn()) session_start();

				$this->userName = $user;
				$_SESSION["br-user"] = $user;
				$_SESSION["br-hash"] = $this->makeHash($this->userName, $this->settings->salt);

				$passwordFile->close();
				return true;
			}
		}

		$passwordFile->close();
		return false;
	}

	public function authorize(string $path): bool {
		if (empty($path)) return true;

		if (isset($this->cachedPath[$path])) {
			if ($this->cachedPath[$path]) return $this->authorize(substr($path, 0, strrpos($path, "/")));
			return false;
		}

		$accessFile = new File($this->folder, $path."/".$this->settings->accessFile);
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

		$this->userName = null;
		session_destroy();
	}

	public function getLoggedInUser(): string {
		return $this->userName;
	}

	public function printLoggedInUser() {
		print $this->userName;
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
		if (!session_status() != PHP_SESSION_ACTIVE) return false;

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
