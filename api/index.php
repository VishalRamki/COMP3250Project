<?php
// REQUIRE STUFF


include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.


$connection = new mysqli($server, $user, $pass, $dbname);

// TYPED

$const_INT = gettype(0);
$const_STR = gettype("STRING");

// BASIC API STRUCTURE

$url_elements = array();

if (isset($_SERVER['PATH_INFO'])) {
	$url_elements = explode('/', trim($_SERVER['PATH_INFO'], '/')); // Collect URL DATA
}
if (count($url_elements) == 0) {
	$response = "API_DATA";
} else {
	
	switch(strtoupper($url_elements[0])) {
		
		case "CUSTOMER":
			
			$tbl = "Customer";
			$query = $stmt_select_all_customer;
				
			break;
			
		case "DISH":
			
			$tbl = "Dish";
			$query = $stmt_select_all_dish;
				
			break;
			
		case "INGREDIENT":
			
			$tbl = "Ingredients";
			$query = $stmt_select_all_ingredients;
				
			break;
			
		case "ORDER":
			
			$tbl = "Order";
			$query = $stmt_select_all_orders;
				
			break;
			
		case "PRICE":
			
			$tbl = "Price";
			$query = $stmt_select_all_prices;
				
			break;
		
		default:
			echo "Your Request Was Not Inputted Properly.";
			break;
		
	}
				
	$counter = 0;
	$bindings = "";
	$params = array();
	$identifers = array();
	$start = 1;
	$end = count($url_elements) - 1;
			
	for ($x = $start; $x <= $end; $x++) {
		// First Check For Identifier followed by pushing the data;
		array_push($identifers, getColumn($url_elements[$x++], $tbl));
		array_push($params, $url_elements[$x]);
		if (gettype($url_elements[$x]) == $const_STR)
			$bindings .= 's';
		else if (gettype($url_elements[$x]) == $const_INT)
			$bindings .= 'i';
	}
				
	if (count($identifers) > 0 ) {
					
		$query .= " WHERE " . $identifers[0] . " = ? ";
					
		for ($y = 1; $y < count($identifers); $y++){
			$query .= "AND " . $identifers[$y] . " = ? ";	
		}
			
	}
				
	$response = mysqli_prepared_query($connection, $query, $bindings, $params);
}


$connection->close();

header('Content-Type: application/json');
echo json_encode($response);
	

?>
