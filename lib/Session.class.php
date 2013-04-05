<?php

include ("File.class.php");

class Session {

	private $userName;
	private $fileSystemHandler;
	private $passwdFile;
	private $accessFile;
	private $salt;

	public function __construct($fileSystemHandler) {
		$this->fileSystemHandler = $fileSystemHandler;
		$this->passwdFile = defined("PASSWD_FILE")?PASSWD_FILE:DEF_PASSWD_FILE;
		$this->accessFile = defined("ACCESS_FILE")?ACCESS_FILE:DEF_ACCESS_FILE;
		$this->salt = defined("SALT")?SALT:null;

		$this->init();
	}

	public function authenticate($user, $password) {
		if(empty($user) || empty($password)) return;

		$passwordFile = new File($this->fileSystemHandler);
		$passwordFile->open($this->passwdFile);

		while($passwordFile->hasNext()) {
			$r = $passwordFile->readLine();
			if(strpos($r, $user.":".$password) === 0) {
				if(!$this->isLoggedIn()) session_start();

				$this->userName = $user;
				$_SESSION["user"] = $user;
				$_SESSION["hash"] = $this->makeHash($this->userName, $this->salt);

				$passwordFile->close();
				return true;
			}
		}

		$passwordFile->close();
		return false;
	}

	public function authorize($path) {
		$accessFile = new File($this->fileSystemHandler);
		if($accessFile->open($path.'/'.$this->accessFile) == false) return true;

		while($accessFile->hasNext()) {
			$r = $accessFile->readLine();
			if(strpos($r, $this->userName) === 0) return true;
		}

		return false;
	}

	public function clear() {
		if(!$this->isLoggedIn()) return;

		$this->userName = null;
		session_destroy();
	}

	public function getLoggedInUser() {
		return $this->userName;
	}

	public function printLoggedInUser() {
		print $this->userName;
	}

	public function isLoggedIn() {
		return session_status() == PHP_SESSION_ACTIVE;
	}

	private function init() {
		if($this->isLoggedIn()) return true;
		if($this->salt === null) return false;

		session_start();

		if(isset($_SESSION["hash"]) && isset($_SESSION["user"])) {
			if($this->makeHash($_SESSION["user"], $this->salt) == $_SESSION["hash"]) {
				$this->userName = $_SESSION["user"];
				return true;
			}
		}

		$this->userName == null;
		session_destroy();
		return false;
	}

	private function makeHash($user, $salt) {
		return md5($user.$salt);
	}
}

?>