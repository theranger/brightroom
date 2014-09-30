<?php

include ("lib/FileSystemHandler.class.php");
include ("lib/URLParser.class.php");
include ("lib/Layout.class.php");
include ("lib/Session.class.php");
include ("config.inc.php");

//Force HTTPS if needed
if(defined("FORCE_HTTPS") && FORCE_HTTPS == true && !isset($_SERVER["HTTPS"])) {
	header("Location:https://".$_SERVER["SERVER_NAME"].$_GET["sfg-q"]);
	die();
}

$fsh = new FileSystemHandler(DATA_DIR);
$session = new Session($fsh);
$layout = new Layout($fsh, $session);

if(!isset($_GET["sfg-q"])) $_GET["sfg-q"] = "/";
$urlParser = new URLParser($_GET["sfg-q"], $fsh);

//Check if we have the permission to view URL
if(!$session->authorize($urlParser->getDirectory())) {
	print 'You don\'t have permission to view this object';
	die();
}

//If this URL points to full screen image
if($urlParser->isFullImage()) {
	if(!$urlParser->isDirectory()) $layout->getFile($urlParser->getURL(), isset($_GET["sfg-size"])?$_GET["sfg-size"]:null);
	die();
}

if(!$urlParser->isValid()) die();

//Logout, if requested
if(isset($_GET["sfg-logout"])) {
	$session->clear();
	header("Location: /");
	die();
}

//Authenticate, if login in progress
if(isset($_POST["sfg-user"]) && isset($_POST["sfg-pass"])) {
	$session->authenticate($_POST["sfg-user"], $_POST["sfg-pass"]);
}

//Set URL parser to be used in layout
$layout->setURLParser($urlParser);

?>