<?php

include("File.php");

class Session {

	private $userName;
	private $fileSystemHandler;
	private $passwdFile;
	private $accessFile;
	private $salt;
	private $cachedPath = array();

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

		$token = $user.":{SHA}".base64_encode(sha1($password, true));

		while($passwordFile->hasNext()) {
			$r = $passwordFile->readLine();
			if(strpos($r, $token) === 0) {
				if(!$this->isLoggedIn()) session_start();

				$this->userName = $user;
				$_SESSION["sfg-user"] = $user;
				$_SESSION["sfg-hash"] = $this->makeHash($this->userName, $this->salt);

				$passwordFile->close();
				return true;
			}
		}

		$passwordFile->close();
		return false;
	}

	public function authorize($path) {
		if(empty($path)) return true;

		if(isset($this->cachedPath[$path])) {
			if($this->cachedPath[$path]) return $this->authorize(substr($path, 0, strrpos($path,"/")));
			return false;
		}

		$accessFile = new File($this->fileSystemHandler);
		if($accessFile->open($path.'/'.$this->accessFile) == false) {
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

	public function getLoggedInUser() {
		return $this->userName;
	}

	public function printLoggedInUser() {
		print $this->userName;
	}

	public function isLoggedIn() {
		return (isset($_SESSION["sfg-hash"]));
	}

	private function init() {
		if($this->isLoggedIn()) return true;
		if($this->salt === null) return false;

		//Try to start the session only if headers have not been sent and session is not already started
		if(!headers_sent() && session_status() == PHP_SESSION_NONE) {
			session_name(DEF_SESSION_NAME);
			session_start();
		}

		//No session exists, nothing to do
		if(!session_status() != PHP_SESSION_ACTIVE) return false;

		if(isset($_SESSION["sfg-hash"]) && isset($_SESSION["sfg-user"])) {
			if($this->makeHash($_SESSION["sfg-user"], $this->salt) == $_SESSION["sfg-hash"]) {
				$this->userName = $_SESSION["sfg-user"];
				return true;
			}
		}

		$this->userName == null;

		//Destroy only if this is our session
		if(session_name() == DEF_SESSION_NAME) session_destroy();
		return false;
	}

	private function makeHash($user, $salt) {
		return md5($user.$salt);
	}
}

?>
