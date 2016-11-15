<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/collection.css">
	</head>
	<body>
		<aside>
			<header></header>
			<nav><?php UINavigation::PrintTree() ?></nav>
		</aside>

		<main>
			<header>

			</header>
			<article>
				<?php UICollection::PrintThumbnails(); ?>
			</article>
		</main>
	</body>
</html>
