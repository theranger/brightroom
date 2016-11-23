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

set_include_path(dirname(__FILE__)."/lib");

include_once "config.php";
include_once "system/Settings.php";
include_once "net/Request.php";
include_once "net/Router.php";

try {
	$cwd = getcwd();
	chdir(dirname(__FILE__));

	$settings = new Settings($GLOBALS["settings"]);
	$router = new Router($settings);

	// When invoking with PHP built-in server using router script, REDIRECT_URL is not set
	// Using Apache mod_rewrite, PHP_SELF would be set to index.php
	$request = new Request(isset($_SERVER["REDIRECT_URL"]) ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"], $settings);

	$router->route($request);
	chdir($cwd);
}
catch(Exception $ex) {
	print $ex->getMessage();
}

