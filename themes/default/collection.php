<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<?php UI::PrintHeader() ?>
</head>
<body>
<div class="sfg-sidebar">
	<?php UINavigation::PrintTree() ?>
</div>
<div class="sfg-main">
	<div><?php UINavigation::PrintBreadcrumb(); ?></div>
	<?php UICollection::PrintThumbnails(); ?>
</div>
</body>
</html>
