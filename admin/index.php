<?php

include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.

require_once("../template/tpl.engine.php"); // Template Enigne;

$connection = new mysqli($server, $user, $pass, $dbname);

if($connection->connect_errno > 0){
    die('Unable to connect to database [' . $connection->connect_error . ']');
}

$page = new Template("../template/admin/base.html");

$page->set("Page-Title", "Welcome To Admin Panel v0.0");

$links = new MultiView("../template/admin/li.html");

$linkData = array(
	array("index.php", "Home"),
	array("dish_manager.php", "View/Edit Dishes"),
	array("order_manager.php", "View Orders"),
	array("insert.php", "Insert"),
	array("index.php", "Home"),
);

$linkParams = array("url-link", "url-title");

$links->buildMultiStack($linkData, $linkParams);

$page->set("Page-Home-Links", $links->mergeMultiStack());

$connection->close();

echo $page->output();
?>
