<?php

include_once "Session.php";
include_once "io/FileSystemHandler.php";
include_once "URLParser.php";
include_once "Layout.php";
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
	private $fileSystemHandler;

	public function __construct(string $url, Settings $settings, FileSystemHandler $fileSystemHandler) {
		$this->fileSystemHandler = $fileSystemHandler;
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

	private function parseRequest() {
		$this->acceptedType = ContentType::getType($_SERVER["HTTP_ACCEPT"]);

		if ($this->fileSystemHandler->isDirectory($this->urlParser->getURL())) {
			$this->requestType = RequestType::IMAGE_FOLDER;
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

		if (!$this->fileSystemHandler->exists($this->urlParser->getURL())) {
			$this->requestType = RequestType::INVALID;
			return;
		}

		$this->requestType = RequestType::IMAGE_FILE;
	}
}
