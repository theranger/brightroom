<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/image.css">
		<link rel="stylesheet" type="text/css" href="<?php UI::PrintThemeUrl() ?>/css/font-awesome.min.css">
	</head>
	<body>
		<aside>
			<header><a href="/?about"><img src="<?php UI::PrintThemeUrl() ?>/images/Icon@30.png" alt="Brightroom Icon"></a></header>
			<nav><a href="<?php UICollection::PrintURL(); ?>"><i class="fa fa-backward fa-lg" aria-hidden="true"></i></a></nav>
			<nav><a href="#image"><i class="fa fa-picture-o fa-lg" aria-hidden="true"></i></a></nav>

			<?php if (UIImage::HasTitle()): ?>
			<nav><a href="#caption"><i class="fa fa-font fa-lg" aria-hidden="true"></i></a></nav>
			<?php endif; ?>

			<?php if (UIImage::HasExif()): ?>
			<nav><a href="#info"><i class="fa fa-camera fa-lg" aria-hidden="true"></i></a></nav>
			<?php endif; ?>

		</aside>

		<main>
			<a name="image"></a>
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
			<section>
				<figure>
					<a name="caption"></a>
					<img src="<?php UIImage::PrintImageURL() ?>" alt="Test image"/>

					<?php if (UIImage::HasTitle()): ?>
					<figcaption><?php UIImage::PrintTitle(); ?></figcaption>
					<?php endif; ?>
				</figure>

				<?php if (UIImage::HasExif()): ?>
				<article>
					<a name="info"></a>
					<?php UIImage::PrintExif(); ?>
				</article>
				<?php endif; ?>

			</section>
		</main>
	</body>
</html>
