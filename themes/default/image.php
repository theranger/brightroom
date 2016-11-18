<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/image.css">
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/css/font-awesome.min.css">
	</head>
	<body>
		<aside>
			<header><img src="<?php UI::PrintThemeUrl() ?>/images/Icon@30.png" alt="Brightroom Icon"></header>
			<nav><a href="<?php UICollection::PrintURL(); ?>"><i class="fa fa-backward fa-lg" aria-hidden="true"></i></a></nav>
		</aside>

		<main>
			<header>
				<nav>
				<?php if (UIImage::hasPreviousImage()): ?>
					<a href="<?php UIImage::PrintPreviousImageURL(); ?>" class="nav"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
				<?php endif; ?>
				</nav>

				<nav><?php UINavigation::PrintBreadcrumb(); ?></nav>

				<nav>
				<?php if (UIImage::hasNextImage()): ?>
					<a href="<?php UIImage::PrintNextImageURL(); ?>" class="nav"><i class="fa fa-arrow-circle-o-right fa-2x" aria-hidden="true"></i></a>
				<?php endif; ?>
				</nav>

			</header>
			<article>
				<img src="<?php echo $_SERVER['PHP_SELF']; ?>" alt="Test image"/>
			</article>
		</main>
	</body>
</html>
