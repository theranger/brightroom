<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
	</head>
	<body>
		<aside>
			<?php UINavigation::PrintTree() ?>
		</aside>
		<main>
			<nav><?php UINavigation::PrintBreadcrumb(); ?></nav>
			<?php UICollection::PrintThumbnails(); ?>
		</main>
	</body>
</html>
