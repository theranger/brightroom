<?php if(!isset($_GET["sfg-ajax"])) :?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Simple Folder Gallery</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="author" content="The Ranger (ranger.risk.ee)" />
		<meta name="description" content="My photo site provided by simple folder based gallery engine" />
		<meta name="generator" content="Simple Folder Gallery <?php $this->layout->printVersion(); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php $this->layout->printThemeURL(); ?>/style.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="<?php $this->layout->printThemeURL(); ?>/default.js"></script>
	</head>
	<body>
		<div class="sfg-sidebar">
			<?php $this->layout->printFolderContents(false); ?>
		</div>
<?php endif; ?>
		<div class="sfg-main">
			<div class="sfg-head"><?php $this->layout->printBreadcrumb(); ?><?php $this->layout->printLoginDialog(); ?></div>
			<div class="sfg-content">
				<?php $this->layout->getImage(1024); ?>
			</div>
		</div>

<?php if(!isset($_GET["sfg-ajax"])) :?>
	</body>
</html>
<?php endif; ?>
