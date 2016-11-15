<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/image.css">
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/css/font-awesome.min.css">
	</head>
	<body>
		<aside>
			<header></header>
			<nav><a href=""><i class="fa fa-backward fa-lg" aria-hidden="true"></i></a></nav>
		</aside>

		<main>
			<header>
				<nav><a href="" class="nav"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a></nav>
				<nav><?php UINavigation::PrintBreadcrumb(); ?></nav>
				<nav><a href="" class="nav"><i class="fa fa-arrow-circle-o-right fa-2x" aria-hidden="true"></i></a></nav>
			</header>
			<article>
				<img src="<?php echo $_SERVER['PHP_SELF']; ?>" alt="Test image"/>
			</article>
		</main>
	</body>
</html>
