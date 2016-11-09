<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
	</head>
	<body>
		<main class="br-image">
			<article class="br-image">
				<nav><?php UINavigation::PrintBreadcrumb(); ?></nav>
				<img src="<?php echo $_SERVER['PHP_SELF']; ?>" alt="Test image"/>
			</article>
		</main>
	</body>
</html>
