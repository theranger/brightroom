<?php include("lib/index.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="/main.css" />
	</head>
	<body>
		<div class="sidebar"></div>

		<div class="content">
			<?php if($layout->isImage()): ?>
				<?php $layout->getImage(600); ?>
				<div class="meta">
					<table>
						<tr><td>Name:</td><td><?php $layout->getExif()->printTitle(); ?></td></tr>
						<tr><td>File size:</td><td><?php $layout->getExif()->printFileSize(); ?></td></tr>
					</table>
				</div>
			<?php else: ?>
				<div class="meta">
					<p><?php $layout->printReadme(); ?></p>
					<table>
						<tr><td>File count:</td><td><?php $layout->printFileCount(); ?></td></tr>
						<tr><td>Folder size:</td><td><?php $layout->printDirectorySize(); ?></td></tr>
					</table>
				</div>
				<?php $layout->folderListing(); ?>
			<?php endif; ?>
		</div>
	</body>
</html>
