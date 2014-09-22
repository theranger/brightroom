<?php if(!isset($_GET["sfg-ajax"])) :?>
<!--  Begin Folder Gallery Section -->

<link rel="stylesheet" type="text/css" href="<?php $layout->printThemeURL(); ?>/style.css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="<?php $layout->printThemeURL(); ?>/default.js"></script>

<div class="sfg-section">
<?php endif; ?>
	<div class="sfg-main">
		<div class="sfg-head"><?php $layout->printBreadcrumb(); ?></div>
		<div class="sfg-content">
			<?php $layout->getImage(1024); ?>
		</div>
	</div>

<?php if(!isset($_GET["sfg-ajax"])) :?>
</div>

<!--  End Folder Gallery Section -->
<?php endif; ?>