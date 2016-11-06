<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<?php UI::PrintHeader() ?>
</head>
<body>
<div class="sfg-sidebar">
	<?php UICollection::PrintFolders() ?>
</div>
<div class="sfg-main">
	<?php
	foreach (UICollection::getItems() as &$item) {
		if ($item->isDirectory()) continue;
		echo '<a href="' . $item->getURL() . '"><img src="' . $item->getURL() . '?thumbnail=true" alt="' . $item->getName() . '" /></a>';
	}
	?>
</div>
</body>
</html>
