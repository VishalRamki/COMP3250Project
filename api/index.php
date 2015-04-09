<?php
// REQUIRE STUFF


include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.

$connection = new mysqli($server, $user, $pass, $dbname);

// BASIC API STRUCTURE

$url_elements = array();

if (isset($_SERVER['PATH_INFO'])) {
	$url_elements = explode('/', trim($_SERVER['PATH_INFO'], '/')); // Collect URL DATA
}
if (count($url_elements) == 0) {
	$response = "{[API_DATA]}";
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
	$sort_params = array();
	$sort_ids = array();
	$sort_bindings = "";
	$start = 1;
	$end = count($url_elements)-1;
			
	for ($x = $start; $x <= $end; $x++) {
		// First Check For Identifier followed by pushing the data;
		
		if (strtoupper($url_elements[$x]) == "SORT") {
			// move forward 
			array_push($sort_ids, $url_elements[$x++]);
			array_push($sort_ids, getColumn($url_elements[$x++], $tbl));
			if ($x <= $end) array_push($sort_ids, $url_elements[$x]);
		} else if (strtoupper($url_elements[$x]) == "LIMIT") {
			array_push($sort_ids, $url_elements[$x++]);
			if ($x <= $end) array_push($sort_ids, $url_elements[$x]);
		} else {
			array_push($identifers, getColumn($url_elements[$x++], $tbl));
			array_push($params, $url_elements[$x]);
			if (gettype($url_elements[$x]) == $const_STR)
				$bindings .= 's';
			else if (gettype($url_elements[$x]) == $const_INT)
				$bindings .= 'i';
		}
		

	}
				
	if (count($identifers) > 0 ) {
					
		$query .= " WHERE " . $identifers[0] . " = ? ";
					
		for ($y = 1; $y < count($identifers); $y++){
			$query .= "AND " . $identifers[$y] . " = ? ";	
		}
			
	}
	
	if (count($sort_ids) > 0) {
		for ($y = 0; $y < count($sort_ids); $y++) {
			if (strtoupper($sort_ids[$y]) == "SORT") {
				$y++;
				$query .= " ORDER BY `". $sort_ids[$y]."` ";
			} else if (strtoupper($sort_ids[$y]) == "LIMIT") {
				$query .= " LIMIT ";	
			}
			
			if (strtoupper($sort_ids[$y]) == "ASC") {
				$query .= " ASC ";	
			} else if (strtoupper($sort_ids[$y]) == "DESC") {
				$query .= " DESC ";	
			} else if ($sort_ids[$y] > 0) {
				$query .= $sort_ids[$y];	
			}
		}
	}
	
	echo $query;
				
	$response = mysqli_prepared_query($connection, $query, $bindings, $params);
}


$connection->close();

header('Content-Type: application/json');
echo json_encode($response);
	

?>
