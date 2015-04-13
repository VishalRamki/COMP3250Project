<?php

function mysqli_prepared_query($link, $sql, $typeDef = FALSE, $params = FALSE) {
	/*
	 *	CREDIT TO DARREN @ http://php.net/manual/en/mysqli.prepare.php
	 *	MODIFIED CODE FOR TEAM CUSINE.
	 *
	 *  as an active note, this is what i tried to build, unsucessfully.
	 *  this code makes it ridiculously easy to pull information;
	 *
	 *  USAGE:
	 *
	 *	Single Query, Single Result
	 *
	 *	$query = "SELECT * FROM names WHERE firstName=? AND lastName=?";
	 *	$params = array("Bob","Johnson");
	 *	mysqli_prepared_query($connection,$query,"ss",$params); 
	 *
	 *	Single Query, Multiple Results
	 *
	 *	$query = "SELECT * FROM names WHERE lastName=?";
	 *	$params = array("Smith");
	 *	mysqli_prepared_query($connection,$query,"s",$params);
	 *
	 *  Multiple Query, Multiple Results
	 *
	 *	$query = "SELECT * FROM names WHERE lastName=?";
	 *	$params = array(array("Smith"),array("Johnson"));  
	 *	mysqli_prepared_query($connection,$query,"s",$params);
	 *
	 */
	
	if($stmt = mysqli_prepare($link,$sql)){
		if(count($params) == count($params,1)){
			$params = array($params);
			$multiQuery = FALSE;
		} else {
			$multiQuery = TRUE; 
		}
		
		if($typeDef){
			$bindParams = array();
			$bindParamsReferences = array();
			$bindParams = array_pad($bindParams,(count($params,1)-count($params))/count($params),"");
			
			foreach($bindParams as $key => $value){
				$bindParamsReferences[$key] = &$bindParams[$key]; 
			}
			
			array_unshift($bindParamsReferences,$typeDef);
			$bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
			$bindParamsMethod->invokeArgs($stmt,$bindParamsReferences);
		}
		
		$result = array();
		
		foreach($params as $queryKey => $query){
			if ($typeDef) {
				foreach($bindParams as $paramKey => $value){
					$bindParams[$paramKey] = $query[$paramKey];
				}
			}
			
			$queryResult = array();
			
			if(mysqli_stmt_execute($stmt)){
				
				$resultMetaData = mysqli_stmt_result_metadata($stmt);
				
				if($resultMetaData){
					$stmtRow = array();
					$rowReferences = array();
					
					while ($field = mysqli_fetch_field($resultMetaData)) {
						$rowReferences[] = &$stmtRow[$field->name];
					}
					
					mysqli_free_result($resultMetaData);
					$bindResultMethod = new ReflectionMethod('mysqli_stmt', 'bind_result');
					$bindResultMethod->invokeArgs($stmt, $rowReferences);
					
					while(mysqli_stmt_fetch($stmt)){							
						$row = array();
						foreach($stmtRow as $key => $value){
							$row[$key] = $value;
						}
						
						$queryResult[] = $row;
					}
					
					mysqli_stmt_free_result($stmt);
				} else {
					$queryResult[] = mysqli_stmt_affected_rows($stmt);
				}
			} else {
				$queryResult[] = FALSE;
			}
			$result[$queryKey] = $queryResult;
		}
		
		mysqli_stmt_close($stmt);
	} else {
		echo "Prepare failed: (" . $link->errno . ") " . $link->error;
		$result = FALSE;
	}
	
	if($multiQuery){
		return $result;
	} else {
		return $result[0];
	}
}

function mysqli_prepared_query_insert($link, $sql, $typeDef = FALSE, $params = FALSE) {
	/*
	 *	BUILT FROM DARREN @ http://php.net/manual/en/mysqli.prepare.php 's PREPARED SELECT STATEMENT;
	 *	
	 *	It uses the same function call as mysqli_prepared_query but instead of receiving data,
	 *	it returns true or false if the query works
	 *	The function still requires testing, but with this, we should be able to build the 
	 *	entire app using these two functions. At least that is the dream.
	 */
	if($stmt = mysqli_prepare($link,$sql)){
		if(count($params) == count($params,1)){
			$params = array($params);
			$multiQuery = FALSE;
		} else {
			$multiQuery = TRUE; 
		}
		
		if($typeDef){
			$bindParams = array();
			$bindParamsReferences = array();
			$bindParams = array_pad($bindParams,(count($params,1)-count($params))/count($params),"");
			
			foreach($bindParams as $key => $value){
				$bindParamsReferences[$key] = &$bindParams[$key]; 
			}
			
			array_unshift($bindParamsReferences,$typeDef);
			$bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
			$bindParamsMethod->invokeArgs($stmt,$bindParamsReferences);
		}
		
		$result = array();
		foreach($params as $queryKey => $query){
			if ($typeDef) {
				
				foreach($bindParams as $paramKey => $value){
					$bindParams[$paramKey] = $query[$paramKey];
				}
			
			}
			
			$queryResult = array();
			
			if(mysqli_stmt_execute($stmt)){
				$ex = true;
			} else {
				$ex = false;
			}
		}
		
		mysqli_stmt_close($stmt);
	} else {
		echo "Prepare failed: (" . $link->errno . ") " . $link->error;
		$result = FALSE;
	}
	
	return $ex;
}

function mysqli_prepared_query_mod($link, $sql, $typeDef = FALSE, $params = FALSE) {
	/*
	 *	BUILT FROM DARREN @ http://php.net/manual/en/mysqli.prepare.php 's PREPARED SELECT STATEMENT;
	 *	
	 *	It uses the same function call as mysqli_prepared_query but instead of receiving data,
	 *	it returns true or false if the query works
	 */
	if($stmt = mysqli_prepare($link,$sql)){
		if(count($params) == count($params,1)){
			$params = array($params);
			$multiQuery = FALSE;
		} else {
			$multiQuery = TRUE; 
		}
		
		if($typeDef){
			$bindParams = array();
			$bindParamsReferences = array();
			$bindParams = array_pad($bindParams,(count($params,1)-count($params))/count($params),"");
			
			foreach($bindParams as $key => $value){
				$bindParamsReferences[$key] = &$bindParams[$key]; 
			}
			
			array_unshift($bindParamsReferences,$typeDef);
			$bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
			$bindParamsMethod->invokeArgs($stmt,$bindParamsReferences);
		}
		
		$result = array();
		foreach($params as $queryKey => $query){
			if ($typeDef) {
				
				foreach($bindParams as $paramKey => $value){
					$bindParams[$paramKey] = $query[$paramKey];
				}
			
			}
			
			$queryResult = array();
			
			if(mysqli_stmt_execute($stmt)){
				$ex = true;
			} else {
				$ex = false;
			}
		}
		
		mysqli_stmt_close($stmt);
	} else {
		echo "Prepare failed: (" . $link->errno . ") " . $link->error;
		$ex = false;
		$result = FALSE;
	}
	
	return $ex;
}

function inside_array($str, $limits) {
	for ($i = 0; $i < count($limits); $i++) {
		if ($str == $limits[$i]) return 0;
	}
	return 1;
}

function createQuery($baseQuery, $queryType = NULL, $params, $limiters) {
	/*
		PARAMS SHOULD BE PASSED IN THE ORDER THEY NEED TO APPEAR;
		FOR EXAMPLE:
		IF YOU NEED TO UPDATE ON A PARTICULAR ID
		THEN IN ORDER TO ATTAIN WHERE ID = ?
		YOU HAVE TO SPECIFY ID AS THE LAST PARAM VALUE
	*/
	if ($queryType == NULL) {
		return $baseQuery;
	} else {
	
		if ($queryType == "UPDATE") {
			$baseQuery .= " SET ";
			$firstComma = 0;
			for ($i = 0; $i < count($params); $i++) {
				if (!inside_array($params[$i], $limiters)) $i++;				
				if ($firstComma == 1) $baseQuery .= ', ';
				$baseQuery .= "`".$params[$i]."` = ? ";
				if ($firstComma == 0) $firstComma = 1;
			}
			$baseQuery .= " WHERE `".$limiters[0]."` = ?";
		
			for ($i = 1; $i < count($limiters); $i++) {
				if ($i > 0)	$baseQuery .= ' AND ';
				$baseQuery .= "`".$limiters[$i]."` = ?";
			}
		}
	
	}
	return $baseQuery;	
}

function buildBindings($params) {
	// CONSTANTS
	$const_INT = gettype(0);
	$const_STR = gettype("STRING");
	$bindings = "";
	
		foreach ($params as $param) {
			if (gettype($param) == $const_INT) $bindings .= 'i';
			else if (gettype($param) == $const_STR) $bindings .= 's';
		}
	return $bindings;
}

function sanitize($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;	
}


function getColumn($input, $table) {
	$hashes = array(
		"Customer" => array(
			"ID" => "CustomerID",
			"NAME" => "CustomerName",
			"NUMBER" => "PhoneNumber",
			"ADDRESS" => "Address", 
			"EMAIL" => "Email",
			"VICINITY" => "Vicinity"
		),
		"Dish" => array(
			"ID" => "DishID",
			"NAME" => "DishName",
			"TYPE" => "DishType"
		),
		"Order" => array(
			"ID" => "OrderID",
			"CUSTOMER" => "CustomerID",
			"PAID" => "Paid",
			"PICKUP" => "PickUp",
			"DELIVERY" => "Deffered Delivery"
		),
		"Ingredient" => array(
			"ID" => "IngredientID",
			"NAME" => "IngredientName",
			"TYPE" => "IngredientType"
		),
		"Ingredient_List" => array(
			"ID" => "IngredientListID",
			"INGREDIENT" => "IngredientID",
			"DISH" => "DishID"
		)
	);
	$in = strtoupper($input);
	$out = $hashes[$table][$in];	
	return $out;
}

?>
