<?php
// REQUIRE STUFF


include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.

$connection = new mysqli($server, $user, $pass, $dbname);

// BASIC API STRUCTURE
$response = "";
$tbl = "";
$url_elements = array();

if (isset($_SERVER['PATH_INFO'])) {
	$url_elements = explode('/', trim($_SERVER['PATH_INFO'], '/')); // Collect URL DATA
}
if (count($url_elements) == 0) {
	$response = "API_DATA";
} else {
	
	$isPost = 1; // no positing
	$curr = 0;
	if (strtoupper($url_elements[$curr]) == "POST") {
		// Posting Data To The API.
		$isPost = 0;
		$curr = 1;
	}
	
	switch(strtoupper($url_elements[$curr])) {
		
		case "CUSTOMER":
			
			$tbl = "Customer";
			$query = $stmt_select_all_customer;
				
			break;
			
		case "DISH":
			
			$tbl = "Dish";
			$query = $stmt_select_all_dish;
				
			break;
			
		case "INGREDIENT":
			
			$tbl = "Ingredient";
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
			$response .= "Your Request Was Not Inputted Properly.";
			break;
		
	}
	
	if ($isPost == 0) {
		// Post Stuff.	
		$response .= "We Are Posting Stuff.";
		
		if (strtoupper($tbl) == "ORDER") {
	
			$customer = sanitize($_POST["CustomerID"]);
			$totalCost = sanitize($_POST["TotalCost"]);
			$def_deli = sanitize($_POST["Deffered_Delivery"]);
			$paid = sanitize($_POST["Paid"]);
			$status = sanitize($_POST["Status"]);
			$pickup = sanitize($_POST["PickUp"]);
			$orddets = sanitize($_POST["OrderDetailsID"]);
			
			$params = array($customer, $totalCost, $def_deli, $paid, $status, $pickup, $orddets);
			$bindings = buildBindings($params);
			
			$query = "INSERT INTO orders (`CustomerID`, `TotalCost`, `Deffered Delivery`, `Paid`, `Status`, `PickUp`, `OrderDetailsID`) VALUES(?, ?, ?, ?, ?, ?, ?)";
			
			$res = mysqli_prepared_query_insert($connection, $query, $bindings, $params);
			
			if ($res) {
				$response = 0;  // Sucess	
			} else {
				$response = 1;
			}	
		}
		
	} else {
				
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
				if (strtoupper($url_elements[$x]) == "BIGGER" || strtoupper($url_elements[$x]) == "SMALLER")
					array_push($identifers, $url_elements[$x++]);
				array_push($params, $url_elements[$x]);
				if (gettype($url_elements[$x]) == $const_STR)
					$bindings .= 's';
				else if (gettype($url_elements[$x]) == $const_INT)
					$bindings .= 'i';
			}
			
	
		}
					
		if (count($identifers) > 0 ) {
			$Q = 0;
			$query .= " WHERE " . $identifers[$Q++] . " ";
			
			if (count($identifers) > 1 && strtoupper($identifers[$Q]) == "BIGGER") {
				$query .= "> ? ";
				$Q++;
			} else if (count($identifers) > 1 && strtoupper($identifers[$Q]) == "SMALLER") {
				$query .= "< ? ";
				$Q++;
			} else {
				$query .= "= ? ";
			}
	
			for ($y = $Q; $y < count($identifers); $y++){
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
		
					
		$response = mysqli_prepared_query($connection, $query, $bindings, $params);
	
	}
}


$connection->close();

header('Content-Type: application/json');
echo json_encode($response);
	

?>
