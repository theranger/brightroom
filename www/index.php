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
	return;
}

if(!$urlParser->isValid()) return;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="/main.css" />
	</head>
	<body>
		<div class="sidebar">
			<?php $layout->folderListing($urlParser->getDirectory()); ?>
		</div>

		<div class="content">
			<?php $layout->getImage($urlParser->getURL(), 600); ?>
		</div>
	</body>
</html>
