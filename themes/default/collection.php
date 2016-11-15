<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
	</head>
	<body class="br-collection">
		<aside class="br-collection">
			<header></header>
			<nav><?php UINavigation::PrintTree() ?></nav>
		</aside>

		<main>
			<header>

			</header>
			<article class="br-collection">
				<?php UICollection::PrintThumbnails(); ?>
			</article>
		</main>
	</body>
</html>
