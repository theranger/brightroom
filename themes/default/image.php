<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/image.css">
	</head>
	<body>
		<aside>
			<header></header>
			<nav></nav>
		</aside>

		<main>
			<header>
				<nav>Previous</nav>
				<nav><?php UINavigation::PrintBreadcrumb(); ?></nav>
				<nav>Next</nav>
			</header>
			<article>
				<img src="<?php echo $_SERVER['PHP_SELF']; ?>" alt="Test image"/>
			</article>
		</main>
	</body>
</html>
