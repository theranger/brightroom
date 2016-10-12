<?php

include_once "config.php";
include_once "lib/Request.php";
include_once "lib/Settings.php";
include_once "lib/Router.php";

$settings = new Settings($GLOBALS["settings"]);
$router = new Router($settings);
$request = new Request($_SERVER["REQUEST_URI"], $settings);

$router->route($request);
