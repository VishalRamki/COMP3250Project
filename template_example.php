<?php

include('include/core.db.inc'); // Holds Information Relating to the database
include('include/core.statements.inc'); // PHP mySQL Prepared Statments
include('include/core.db.funcs.inc'); // Reusable mysql functions.

require_once("template/tpl.engine.php"); // Template Enigne;

$connection = new mysqli($server, $user, $pass, $dbname);

if($connection->connect_errno > 0){
    die('Unable to connect to database [' . $connection->connect_error . ']');
}


$rez = mysqli_prepared_query($connection, $stmt_select_all_customer);

$devlayout = new Template($PATH. "default/dev_users_layout.php");
$devusers = new MultiView($PATH. "default/dev_user_data.php");

$devusers->buildMultiStack($rez, array("CustomerID", "CustomerName", "PhoneNumber", "Address", 	"Email", "Vicinity"));

$devlayout->set("user_data", $devusers->mergeMultiStack());

echo $devlayout->output();

$connection->close();

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
