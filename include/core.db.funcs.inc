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
		)
	);
	$in = strtoupper($input);
	$out = $hashes[$table][$in];	
	return $out;
}

?>
