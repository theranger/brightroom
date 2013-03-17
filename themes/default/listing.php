<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="<?php $layout->printThemeURL(); ?>/style.css" />
	</head>
	<body>
		<div class="content">
			<div class="meta">
				<p><?php $layout->printReadme(); ?></p>
				<table>
					<tr><td>File count:</td><td><?php $layout->printFileCount(); ?></td></tr>
					<tr><td>Folder size:</td><td><?php $layout->printDirectorySize(); ?></td></tr>
				</table>
			</div>
			<?php $layout->folderListing(); ?>
		</div>
	</body>
</html>