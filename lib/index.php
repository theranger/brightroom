<?php

include ("lib/FileSystemHandler.class.php");
include ("lib/URLParser.class.php");
include ("lib/Layout.class.php");
include ("config.inc.php");

$fsh = new FileSystemHandler(DATA_DIR);
$layout = new Layout($fsh);
$urlParser = new URLParser($_GET["q"], $fsh);

//If this URL points to full screen image
if($urlParser->isFullImage()) {
	if(!$urlParser->isDirectory()) $layout->getFile($urlParser->getURL(), $_GET["size"]);
	die();
}

if(!$urlParser->isValid()) die();

//Set URL parser to be used in layout
$layout->setURLParser($urlParser);

?>