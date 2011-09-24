<?php

include ("lib/FileSystemHandler.class.php");
include ("lib/Layout.class.php");

define("DATA_DIR", "/files");
define("IMG_PREFIX", "/img");


$fsh = new FileSystemHandler(dirname(__FILE__).DATA_DIR);
$layout = new Layout($fsh);

// detect /img prefix
if(strncmp($_GET["q"], IMG_PREFIX, strlen(IMG_PREFIX)) == 0) {
	$url = substr($_GET["q"], strlen(IMG_PREFIX));
	if(!$fsh->isDirectory($url)) {
		$layout->getFile($url, $_GET["size"]);
	}
	return;
}

$url = $_GET["q"];

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
