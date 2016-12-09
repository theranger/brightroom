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

include_once "Session.php";
include_once "URLParser.php";
include_once "RequestType.php";
include_once "ContentType.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-08
 *
 */
class Request {

	private $urlParser;
	private $settings;
	private $requestType = RequestType::UNKNOWN;
	private $acceptedType = ContentType::PLAIN;
	private $username;
	private $password;

	public function __construct(string $url, Settings $settings) {
		$this->urlParser = new URLParser($url, $settings);
		$this->settings = $settings;
		$this->parseRequest();
	}

	public function getRequestType(): int {
		return $this->requestType;
	}

	public function getAcceptedType(): string {
		return $this->acceptedType;
	}

	public function getURL(): string {
		return $this->urlParser->getURL();
	}

	public function isSecure(): bool {
		return isset($_SERVER["HTTPS"]);
	}

	public function isLogin(): bool {
		return isset($_POST["br-username"]) || isset($_POST["br-password"]);
	}

	public function isLogout(): bool {
		return isset($_GET["logout"]);
	}

	public function getUsername(): string {
		return $this->username;
	}

	public function getPassword(): string {
		return $this->password;
	}

	public function elaborateType(FileSystem $fileSystem) {
		if ($this->requestType != RequestType::UNKNOWN) return;

		switch ($fileSystem->getEntryType()) {
			case EntryType::FILE:
				$this->requestType = RequestType::IMAGE_FILE;
				return;

			case EntryType::FOLDER:
				$this->requestType = RequestType::IMAGE_FOLDER;
				return;
		}

		$this->requestType = RequestType::INVALID;
	}

	private function parseRequest() {
		if (isset($_POST["br-username"])) $this->username = $_POST["br-username"];
		if (isset($_POST["br-password"])) $this->password = $_POST["br-password"];

		$this->acceptedType = ContentType::parseAcceptHeader($_SERVER["HTTP_ACCEPT"]);
		if ($this->acceptedType == ContentType::ANY) $this->acceptedType = ContentType::parseExtension($this->urlParser->getResourceName());

		if (strpos($this->urlParser->getURL(), $this->settings->getThemePrefix()) === 0) {
			$this->requestType = RequestType::THEME_FILE;
			return;
		}

		if (isset($_GET["thumbnail"]) && $_GET["thumbnail"] == true) {
			$this->requestType = RequestType::THUMBNAIL_FILE;
			return;
		}

		if (isset($_GET["about"])) {
			$this->requestType = RequestType::ABOUT_PAGE;
			return;
		}

		if (isset($_GET["login"])) {
			$this->requestType = RequestType::LOGIN_PAGE;
			return;
		}

		$resourceName = $this->urlParser->getResourceName();
		if (empty($resourceName)) return;

		if ($resourceName == $this->settings->accessFile) {
			$this->requestType = RequestType::ACCESS_FILE;
			return;
		}

		if ($resourceName == $this->settings->passwordFile) {
			$this->requestType = RequestType::PASSWORD_FILE;
			return;
		}

		if (strpos($this->settings->vetoFolders, '/'.$resourceName.'/') !== false) {
			$this->requestType = RequestType::VETO_FILE;
			return;
		}
	}
}
