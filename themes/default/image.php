<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<?php UI::PrintHeader() ?>
</head>
<body>
<div class="sfg-sidebar">
	<?php UICollection::PrintFiles() ?>
</div>
<div class="sfg-main">
	<img src="<?php echo $_SERVER['PHP_SELF']; ?>" alt="Test image"/>
</div>
</body>
</html>
