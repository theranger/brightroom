<?php

include_once "io/File.php";

class Session {

	private $userName;
	private $fileSystemHandler;
	private $settings;
	private $cachedPath = array();

	public function __construct(FileSystemHandler &$fileSystemHandler, Settings &$settings) {
		$this->fileSystemHandler = $fileSystemHandler;
		$this->settings = $settings;
		$this->init();
	}

	public function authenticate(string $user, string $password): bool {
		if(empty($user) || empty($password)) return false;

		$passwordFile = new File($this->fileSystemHandler);
		$passwordFile->open($this->settings->passwordFile);

		$token = $user.":{SHA}".base64_encode(sha1($password, true));

		while($passwordFile->hasNext()) {
			$r = $passwordFile->readLine();
			if(strpos($r, $token) === 0) {
				if(!$this->isLoggedIn()) session_start();

				$this->userName = $user;
				$_SESSION["sfg-user"] = $user;
				$_SESSION["sfg-hash"] = $this->makeHash($this->userName, $this->settings->salt);

				$passwordFile->close();
				return true;
			}
		}

		$passwordFile->close();
		return false;
	}

	public function authorize(string $path): bool {
		if(empty($path)) return true;

		if(isset($this->cachedPath[$path])) {
			if($this->cachedPath[$path]) return $this->authorize(substr($path, 0, strrpos($path,"/")));
			return false;
		}

		$accessFile = new File($this->fileSystemHandler);
		if($accessFile->open($path.'/'.$this->settings->accessFile) == false) {
			$this->cachedPath[$path] = true;
			return $this->authorize(substr($path, 0, strrpos($path,"/")));
		}

		while($accessFile->hasNext()) {
			$r = $accessFile->readLine();
			if(strpos($r, $this->userName) === 0) {
				$this->cachedPath[$path] = true;
				return $this->authorize(substr($path, 0, strrpos($path,"/")));
			}
		}

		$this->cachedPath[$path] = false;
		return false;
	}

	public function clear() {
		if(!$this->isLoggedIn()) return;

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
		return isset($_SESSION["sfg-hash"]);
	}

	private function init(): bool {
		if($this->isLoggedIn()) return true;
		if($this->settings->salt === null) return false;

		//Try to start the session only if headers have not been sent and session is not already started
		if(!headers_sent() && session_status() == PHP_SESSION_NONE) {
			session_name($this->settings->sessionName);
			session_start();
		}

		//No session exists, nothing to do
		if(!session_status() != PHP_SESSION_ACTIVE) return false;

		if(isset($_SESSION["sfg-hash"]) && isset($_SESSION["sfg-user"])) {
			if($this->makeHash($_SESSION["sfg-user"], $this->settings->salt) == $_SESSION["sfg-hash"]) {
				$this->userName = $_SESSION["sfg-user"];
				return true;
			}
		}

		$this->userName = "";

		//Destroy only if this is our session
		if(session_name() == $this->settings->sessionName) session_destroy();
		return false;
	}

	private function makeHash(string $user, string $salt): string {
		return md5($user.$salt);
	}
}
