<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/collection.css">
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/static.css">
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/css/font-awesome.min.css">
	</head>
	<body>
		<aside>
			<header>
				<a href="?about"><img src="<?php UI::PrintThemeUrl() ?>/images/Icon@30.png" alt="Brightroom Icon"></a>
			</header>
			<nav><?php UINavigation::PrintTree() ?></nav>
		</aside>

		<main>
			<header><img src="<?php UI::PrintThemeUrl() ?>/images/Logo@30.png" alt="Brightroom Logo"></header>
			<article>
				<?php UI::PrintContent(); ?>
			</article>
		</main>
	</body>
</html>
