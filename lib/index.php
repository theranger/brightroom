<?php

include ("lib/FileSystemHandler.class.php");
include ("lib/URLParser.class.php");
include ("lib/Layout.class.php");
include ("lib/Session.class.php");
include ("config.inc.php");

$fsh = new FileSystemHandler(DATA_DIR);
$session = new Session($fsh);
$layout = new Layout($fsh, $session);
$urlParser = new URLParser($_GET["q"], $fsh);

//Check if we have the permission to view URL
if(!$session->authorize($urlParser->getDirectory())) {
	print 'You don\'t have permission to view this object';
	die();
}

//If this URL points to full screen image
if($urlParser->isFullImage()) {
	if(!$urlParser->isDirectory()) $layout->getFile($urlParser->getURL(), isset($_GET["size"])?$_GET["size"]:null);
	die();
}

if(!$urlParser->isValid()) die();

//Logout, if requested
if(isset($_GET["logout"])) {
	$session->clear();
}

//Authenticate, if login in progress
if(isset($_POST["user"]) && isset($_POST["pass"])) {
	$session->authenticate($_POST["user"], $_POST["pass"]);
}

//Set URL parser to be used in layout
$layout->setURLParser($urlParser);

?>