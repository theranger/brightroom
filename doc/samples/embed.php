<?php
/**
 * Copyright 2016 The Ranger <ranger@risk.ee>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//Handle ajax request and strip container
if (isset($_GET["sfg-ajax"])) {

	//See below for documentation about following includes
	include("config.inc.php");
	include("../../index.php");

	return;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>This is the sample how to embed Simple Folder Gallery</title>
</head>

<body>
<h1>Embedding Sample</h1>
<p>This sample demonstrates how to embed the gallery into existing web page.</p>

<?php
//If embedded system needs to redefine some configuration constants
//create a separate configuration file and include it beforehand
include("config.inc.php");

//To embed, simply include main instance index file
//Can be a separate copy of the php script to specify different
//theme and configuration parameters.
include("../../index.php");
?>
<p>Embedded gallery section ended, main document continues...</p>
</body>
</html>
