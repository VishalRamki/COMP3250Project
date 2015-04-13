<?php

$server = 'localhost';
$user   = 'root';
$pass   = '';
$dbname   = 'comp3250';

$GLOBALS["SITE_PATH"] = "http://localhost/COMP3250";
$GLOBALS["API_PATH"] = $GLOBALS["SITE_PATH"]."/api";
$GLOBALS["TPL_PATH"] = $GLOBALS["SITE_PATH"]."/template";

// TYPED

$const_INT = gettype(0);
$const_STR = gettype("STRING");
?>
