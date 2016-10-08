<?php

include_once "config.php";
include_once "lib/Request.php";
include_once "lib/Settings.php";

$settings = new Settings($GLOBALS["settings"]);
$request = new Request($_SERVER["REQUEST_URI"], $settings);
$request->handleRequest();
