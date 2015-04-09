<?php

include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.
$connection = new mysqli($server, $user, $pass, $dbname);

if($connection->connect_errno > 0){
    die('Unable to connect to database [' . $connection->connect_error . ']');
}

if (isset($_GET["order"]) && $_GET["order"] == "true") {
	
	$customer = sanitize($_POST["CustomerID"]);
	$totalCost = sanitize($_POST["TotalCost"]);
	$def_deli = sanitize($_POST["Deffered_Delivery"]);
	$paid = sanitize($_POST["Paid"]);
	$status = sanitize($_POST["Status"]);
	$pickup = sanitize($_POST["PickUp"]);
	$orddets = sanitize($_POST["OrderDetailsID"]);
	
	$params = array($customer, $totalCost, $def_deli, $paid, $status, $pickup, $orddets);
	$bindings = buildBindings($params);
	
	$QUERY = "INSERT INTO orders (`CustomerID`, `TotalCost`, `Deffered Delivery`, `Paid`, `Status`, `PickUp`, `OrderDetailsID`) VALUES(?, ?, ?, ?, ?, ?, ?)";
	
	$res = mysqli_prepared_query_insert($connection, $query, $bindings, $params);
	
	if ($res) {
		echo "Sucessfully Inserted";	
	} else {
		echo "Error.";	
	}
	
} else if (isset($_GET["view"])) {
	
	$json = file_get_contents($GLOBALS["API_PATH"]."/order/id/".$_GET["view"]);
	$arr = json_decode($json, true);
	
	echo "<h1> Order: ".$arr[0]["OrderID"]."</h1>";
	echo "Placed By Customer: ".$arr[0]["CustomerID"]."<br />";
	echo "Total Cost: ".$arr[0]["TotalCost"]."<br />";
	echo "Deffered Delievery: ".$arr[0]["Deffered Delivery"]."<br />";
	echo "Order Placed At: ".$arr[0]["Time"]."<br />";
	echo "Paid: ".$arr[0]["Paid"]."<br />";
	echo "Status: ".$arr[0]["Status"]."<br />";;
	echo "PickUp: ".$arr[0]["PickUp"]."<br />";
	echo "Order Details: ".$arr[0]["OrderDetailsID"]."<br />";
	
	echo "<a href='".$_SERVER["PHP_SELF"]."'>Go Back</a>";

} else {
	// Display Orders;	
	echo "Nothing Selected To Edit;<br />";	
	$json = file_get_contents($GLOBALS["API_PATH"]."/order");
	$arr = json_decode($json, true);
	
	echo "<h1>Current Orders</h1>";
	
	foreach ($arr as $row) {
		echo '<a href="'.$_SERVER["PHP_SELF"].'?view='.$row["OrderID"].'">'.$row["OrderID"].'</a><br />';	
	}
	echo "<br /> <br />";
}

echo "<br /><a href='index.php'>Return To Admin Panel.</a>";


$connection->close();
?>
