<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Simple Folder Gallery</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="author" content="The Ranger (ranger.risk.ee)" />
		<meta name="description" content="My photo site provided by simple folder based gallery engine" />
		<meta name="generator" content="Simple Folder Gallery <?php $this->layout->printVersion(); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php $this->layout->printThemeURL(); ?>/style.css" />
	</head>
	<body>
		<div class="sfg-sidebar">
			<p><?php $this->layout->printReadme(); ?></p>
			<table>
				<tr><td>File count:</td><td><?php $this->layout->printFileCount(); ?></td></tr>
				<tr><td>Folder size:</td><td><?php $this->layout->printDirectorySize(); ?></td></tr>
			</table>

			<?php if(!$this->layout->isRoot()) $this->layout->printFolderTree(); ?>
		</div>
		<div class="sfg-main">
			<div class="sfg-head"><?php $this->layout->printBreadcrumb(); ?><?php $this->layout->printLoginDialog(); ?></div>
			<?php $this->layout->printFolderContents($this->layout->isRoot()); ?>
		</div>
	</body>
</html>
