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
