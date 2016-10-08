<?php

include_once "config.inc.php";
include_once "lib/Request.php";

$request = new Request(isset($_GET["sfg-q"]) ? $_GET["sfg-q"] : "/");
$request->handleRequest();
