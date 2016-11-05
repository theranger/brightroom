<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<?php UI::PrintHeader() ?>
	</head>
	<body>
		<div class="sfg-sidebar">
			<?php UIFolder::PrintFolders() ?>
		</div>
		<div class="sfg-main">
			<?php
				foreach (UIFolder::getItems() as &$item) {
					echo '<img src="'.$item->getURL().'?thumbnail=true" alt="'.$item->getName().'" />';
				}
			?>
		</div>
	</body>
</html>
