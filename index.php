<?php

set_include_path(dirname(__FILE__)."/lib");

include_once "config.php";
include_once "Settings.php";
include_once "net/Request.php";
include_once "net/Router.php";
include_once "io/FileSystemHandler.php";

$cwd = getcwd();
chdir(dirname(__FILE__));

$settings = new Settings($GLOBALS["settings"]);
$fileSystemHandler = new FileSystemHandler($settings->dataDirectory);
$router = new Router($settings, $fileSystemHandler);
$request = new Request($_SERVER["REQUEST_URI"], $settings, $fileSystemHandler);

$router->route($request);
chdir($cwd);
