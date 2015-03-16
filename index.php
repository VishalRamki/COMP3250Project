<?php

/*

	24/02/15 - 9:14PM
	
	Began Development.
	
	Using The Sprint Week Document as Reference.
	
	16/03/15 - 1:56PM
	
	Development was slow due to me not able to write the resusable code.
	Turns out that it was even more complex code than I thought.
	I found the code to do what I wanted, and with little modifcation
	I was able to keep it functional and in-line with the app needs.
	
	Now that we can spit out data and insert data fairly strongly,
	we focus now on spitting data onto a template. This well allow us 
	to keep our Back-Ends and Front-ends from interfering with each other.

*/

include('include/core.db.inc'); // Holds Information Relating to the database
include('include/core.statements.inc'); // PHP mySQL Prepared Statments
include('include/core.db.funcs.inc'); // Reusable mysql functions.

$connection = new mysqli($server, $user, $pass, $dbname);

if($connection->connect_errno > 0){
    die('Unable to connect to database [' . $connection->connect_error . ']');
}

$query = "SELECT * FROM customer WHERE PhoneNumber > ?";
$params = array("100");

//var_dump(mysqli_prepared_query($connection,$query,"i",$params)); 

$query = "SELECT * FROM customer WHERE CustomerID = ?";
$params = array("C003");

//var_dump(mysqli_prepared_query($connection,$query,"s",$params)); 

$query = "SELECT CustomerName FROM customer WHERE CustomerID = ?";
$user = "C004";
$params = array($user);
echo "<br />";
$result = mysqli_prepared_query($connection,$query,"s",$params);
var_dump($result);
echo "<br />Customer Name: " . $result[0]["CustomerName"]; 

$query = "INSERT INTO test(name, number, text) VALUES(?,?,?)";
$name = "Vishal";
$number = 12345;
$text = "Worked.";
$params = array($name, $number, $text);

$result = mysqli_prepared_query_insert($connection, $query, "sis", $params);

if ($result) {
	echo "Insert Successful.";	
} else {
	echo "<br />Insert Unsucessful<br />";	
}

var_dump(mysqli_prepared_query($connection, $stmt_select_all_customer));

$connection->close();

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>COMP3250</title>
</head>

<body>
</body>
</html>
