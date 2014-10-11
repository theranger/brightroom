<?php if(!isset($_GET["sfg-ajax"])) :?>
<!--  Begin Folder Gallery Section -->

<link rel="stylesheet" type="text/css" href="<?php $layout->printThemeURL(); ?>/style.css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="<?php $layout->printThemeURL(); ?>/default.js"></script>

<div class="sfg-nav"><?php $layout->printFolderContents(false); ?></div>

<?php endif; ?>
	<div class="sfg-main">
		<div class="sfg-container">
			<div class="sfg-breadcrumb"><?php $layout->printBreadcrumb(); ?></div>
			<a class="sfg-back" href="<?php $layout->printDirectoryURL(); ?>">X</a>
			<?php $layout->getImage(1024); ?>
		</div>
	</div>

<?php if(!isset($_GET["sfg-ajax"])) :?>

<!--  End Folder Gallery Section -->
<?php endif; ?>