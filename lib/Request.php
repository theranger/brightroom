<?php

include_once "Session.php";
include_once "FileSystemHandler.php";
include_once "URLParser.php";
include_once "Layout.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-08
 *
 */
class Request {

	private $session;
	private $layout;
	private $urlParser;
	private $settings;

	public function __construct(string $url, Settings $settings) {
		$fileSystemHandler = new FileSystemHandler($settings->dataDirectory);
		$this->session = new Session($fileSystemHandler, $settings);
		$this->urlParser = new URLParser($url, $fileSystemHandler);
		$this->layout = new Layout($fileSystemHandler, $this->session, $this->urlParser, $settings);
		$this->settings = $settings;
	}

	public function handleRequest(): bool {
		//Force HTTPS if needed
		if($this->settings->forceHTTPS == true && !isset($_SERVER["HTTPS"])) {
			header("Location:https://".$this->urlParser->getURL());
			return false;
		}

		if (!$this->authorize()) return false;
		if ($this->handleFullImage()) return true;

		if(!$this->urlParser->isValid()) {
			print 'Requested URL is not valid';
			return false;
		}

		//Logout, if requested
		if(isset($_GET["sfg-logout"])) {
			$this->session->clear();
			header("Location: /");
			return false;
		}

		//Authenticate, if login in progress
		if(isset($_POST["sfg-user"]) && isset($_POST["sfg-pass"])) {
			$this->session->authenticate($_POST["sfg-user"], $_POST["sfg-pass"]);
		}

		if ($this->handleSingleImage()) return true;

		return $this->handleDirectory();
	}

	private function authorize(): bool {
		//Check if we have the permission to view URL
		if(!$this->session->authorize($this->urlParser->getDirectory())) {
			print 'You don\'t have permission to view this object';
			return false;
		}

		return true;
	}

	private function handleFullImage(): bool {
		if (!$this->urlParser->isFullImage()) return false;

		if($this->urlParser->isDirectory())
			$this->layout->getBadge($this->urlParser->getURL(), isset($_GET["sfg-size"])?$_GET["sfg-size"]:null);
		else
			$this->layout->getFile($this->urlParser->getURL(), isset($_GET["sfg-size"])?$_GET["sfg-size"]:null);

		return true;
	}

	private function handleSingleImage(): bool {
		if (!$this->layout->isImage()) return false;

		$f = "themes/".$this->layout->getTheme()."/single.php";

		if(!file_exists($f)) {
			print 'Could not load theme file for single image.';
			return true;
		}

		include $f;
		return true;
	}

	private function handleDirectory(): bool {
		$f = "themes/".$this->layout->getTheme()."/listing.php";

		if(!file_exists($f)) {
			print 'Could not load theme file for listing.';
			return true;
		}

		include $f;
		return true;
	}
}
