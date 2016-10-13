<?php

set_include_path(dirname(__FILE__)."/lib");

include_once "config.php";
include_once "net/Request.php";
include_once "Settings.php";
include_once "net/Router.php";

$settings = new Settings($GLOBALS["settings"]);
$router = new Router($settings);
$request = new Request($_SERVER["REQUEST_URI"], $settings);

$router->route($request);
