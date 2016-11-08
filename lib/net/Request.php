<?php

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

		if (strpos($this->settings->vetoFolders, '/' . $resourceName . '/') !== false) {
			$this->requestType = RequestType::VETO_FILE;
			return;
		}
	}
}
