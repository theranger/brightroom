<?php

include ("lib/FileSystemHandler.class.php");
include ("lib/Layout.class.php");
include ("config.inc.php");

$fsh = new FileSystemHandler(DATA_DIR);
$layout = new Layout($fsh);

// detect /img prefix
if(strncmp($_GET["q"], IMG_PREFIX, strlen(IMG_PREFIX)) == 0) {
	$url = $fsh->clearPath(substr($_GET["q"], strlen(IMG_PREFIX)));
	if(!$fsh->isDirectory($url)) {
		$layout->getFile($url, $_GET["size"]);
	}
	return;
}

$url = $fsh->clearPath($_GET["q"]);
if(!$fsh->exists($url) || (defined("CACHE_FOLDER") && basename($url) == CACHE_FOLDER)) {
	echo "Folder does not exist or is not readable";
	return;
}


?>

<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="/main.css" />
	</head>
	<body>
		<div class="sidebar">
			<?php $layout->folderListing($url); ?>
		</div>

		<div class="content">
			<?php $layout->getImage($url, 600); ?>
		</div>
	</body>
</html>
