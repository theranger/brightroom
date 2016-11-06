<div class="sfg-sidebar">
	<?php UIFolder::PrintFolders() ?>
</div>
<div class="sfg-main">
	<?php
	foreach (UIFolder::getItems() as &$item) {
		if ($item->isDirectory()) continue;
		echo '<a href="'.$item->getURL().'"><img src="'.$item->getURL().'?thumbnail=true" alt="'.$item->getName().'" /></a>';
	}
	?>
</div>
