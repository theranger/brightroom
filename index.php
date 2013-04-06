<?php include("lib/index.php");

//Check if we have the permission to view URL
if(!$session->authorize($urlParser->getDirectory())) {
	print 'You don\'t have permission to view this object';
	return;
}

//Load layout for displaying single image
if($layout->isImage()) {
	$f = "themes/".$layout->getTheme()."/single.php";

	if(!file_exists($f)) {
		print 'Could not load theme file for single image.';
		return;
	}

	include $f;
	return;
}



//Load layout for displaying content listing
$f = "themes/".$layout->getTheme()."/listing.php";

if(!file_exists($f)) {
	print 'Could not load theme file for listing.';
	return;
}

include $f;
return;

?>
