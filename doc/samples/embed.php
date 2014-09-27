<html>
	<head>
		<title>This is the sample how to embed Simple Folder Gallery</title>
	</head>
	
	<body>
		<h1>Embedding Sample</h1>
		This sample demonstrates how to embed the gallery into existing web page
		
		<?php
			//Since this sample is not in web root, set the prefix so that
			//future requests would be also served by this script.
			//Note that webroot .htaccess contains exception that allows this
			define("URL_PREFIX", "/doc/samples");
			
			include("../../index.php");
		?>
	</body>
</html>