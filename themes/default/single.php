<?php if(!isset($_GET["ajax"])) :?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Gallery</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="<?php $layout->printThemeURL(); ?>/style.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="<?php $layout->printThemeURL(); ?>/default.js"></script>
	</head>
	<body>
		<div class="sidebar">
			<?php $layout->folderListing(); ?>
		</div>
<?php endif; ?>

		<div class="content">
			<div class="head"><?php $layout->printBreadcrumb(); ?></div>
			<?php $layout->getImage(600); ?>
			<div class="meta">
				<table>
					<tr><td>Name:</td><td><?php $layout->getExif()->printTitle(); ?></td></tr>
					<tr><td>File size:</td><td><?php $layout->getExif()->printFileSize(); ?></td></tr>
				</table>
			</div>
		</div>
		
<?php if(!isset($_GET["ajax"])) :?>
	</body>
</html>
<?php endif; ?>