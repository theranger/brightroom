<?php include("lib/index.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="/main.css" />
	</head>
	<body>
		<div class="sidebar">
			<?php $layout->folderListing(); ?>
		</div>

		<div class="content">
			<?php $layout->getImage(600); ?>
		</div>
	</body>
</html>
