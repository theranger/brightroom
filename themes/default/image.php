<!DOCTYPE html>
<html>
	<head>
		<?php UI::PrintHeader() ?>
	</head>
	<body>
		<main>
			<nav><?php UINavigation::PrintBreadcrumb(); ?></nav>
			<img src="<?php echo $_SERVER['PHP_SELF']; ?>" alt="Test image"/>
		</main>
	</body>
</html>
