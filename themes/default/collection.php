<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
	</head>
	<body>
		<main class="br-collection">
			<aside class="br-collection">
				<?php UINavigation::PrintTree() ?>
			</aside>
			<article class="br-collection">
				<?php UICollection::PrintThumbnails(); ?>
			</article>
		</main>
	</body>
</html>
