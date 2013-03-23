<?php if(!isset($_GET["ajax"])) :?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Simple Folder Gallery</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="author" content="The Ranger (ranger.risk.ee)">
		<meta name="description" content="My photo site provided by simple folder based gallery engine">
		<meta name="generator" content="Simple Folder Gallery <?php $layout->printVersion(); ?>">
		<link rel="stylesheet" type="text/css" href="<?php $layout->printThemeURL(); ?>/style.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="<?php $layout->printThemeURL(); ?>/default.js"></script>
	</head>
	<body>
		<div class="sidebar">
			<?php $layout->printFolderContents(false); ?>
		</div>
<?php endif; ?>
		<div class="main">
			<div class="head"><?php $layout->printBreadcrumb(); ?></div>
			<div class="content">
				<?php $layout->getImage(1024); ?>
			</div>
		</div>

<?php if(!isset($_GET["ajax"])) :?>
	</body>
</html>
<?php endif; ?>