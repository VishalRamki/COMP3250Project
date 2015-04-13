<?php 

include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.
$connection = new mysqli($server, $user, $pass, $dbname);

if($connection->connect_errno > 0){
    die('Unable to connect to database [' . $connection->connect_error . ']');
}
if (isset($_GET["add"]) && $_GET["add"] == "true") {
	
	$name = sanitize($_POST["dishname"]);
	$price = sanitize($_POST["price"]);
	$desc = sanitize($_POST["description"]);
	$type = sanitize($_POST["dishtype"]);
	$ingredients = $_POST["ingredient"];
	
	$params = array($name, $price, $desc, $type);
	$bindings = buildBindings($params);

	$queryBase = "INSERT INTO dish (DishName, PriceID, Description, DishType) VALUES(?, ?, ?, ?)";
	
	$res = mysqli_prepared_query_insert($connection, $queryBase, $bindings, $params);
	$last_id = $connection->insert_id;
	echo "LAST INSERT: ".$last_id;
		
	$queryTags = "INSERT INTO ".$_tblingrli." (`IngredientID`, `DishID`) VALUES (?, ?)";
	
		foreach($ingredients as $in) {
			$paramx2 = array($in, $last_id);
			$binding2s = buildBindings($paramx2);
			$res2 = mysqli_prepared_query_insert($connection, $queryTags, $binding2s, $paramx2);
		}
	
	
	if ($res) {
		echo "Sucessfully Inserted.";	
	} else {
		echo "Error.";	
	}
	echo "<a href='".$_SERVER["PHP_SELF"]."'>Go Back</a>";
	
} else if (isset($_GET["add"]) && $_GET["add"] == "form") {
	echo "<h1>Add Dish</h1>";
?>
<form action="<?php $_SERVER["PHP_SELF"]; ?>?add=true" id="dish_add_form" method="post">
<input type="text" name="dishname" value="" /><br />
<?php 
	$json = file_get_contents($GLOBALS["API_PATH"]."/ingredient");
	$arr = json_decode($json, true);
	
	foreach ($arr as $row) {
		echo '
<input type="checkbox" name="ingredient[]" value="'.$row["IngredientID"].'" /> '.$row["IngredientName"].'('.$row["IngredientType"].') ';	
	}
?><br />

<input type="text" name="price" value="" /><br />
<input type="textarea" name="description" cols="20" rows="10" value="" /><br />
<input type="text" name="dishtype" value="" /><br />
<input type="submit" />
</form>
<?php
} else if (isset($_GET["delete"])) {
	
	$deleteId = sanitize($_GET["delete"]);
	
	$query = "DELETE FROM ".$_tbldish." WHERE DishId = ?";
	$delete = "DELETE FROM ".$_tblingrli." WHERE `DishID` = ".$id;
	
	$qres = mysqli_prepared_query_mod($connection, $delete, FALSE, FALSE);
	$res = mysqli_prepared_query_mod($connection, $query, "s", array($deleteId));
	if ($res) {
		echo $deleteId ." Sucessfully Deleted";	
	} else {
		echo "Error.";	
	}
	echo "<a href='".$_SERVER["PHP_SELF"]."'>Go Back</a>";
	
} else if (isset($_GET["edited"]) && $_GET["edited"] == true) {
	echo "We Are Editing This [".$_POST["id"]."] Entry.<br />";
	echo "[".sanitize($_POST["id"])."]<br />";
	echo "[".sanitize($_POST["dishname"])."]<br />";
	echo "[".sanitize($_POST["price"])."]<br />";
	echo "[".sanitize($_POST["description"])."]<br />";
	echo "[".sanitize($_POST["dishtype"])."]<br />";
	
	$json = file_get_contents($GLOBALS["API_PATH"]."/dish");
	$arr = json_decode($json, true);
	
	$id = sanitize($_POST["id"]);
	$name = sanitize($_POST["dishname"]);
	$price = sanitize($_POST["price"]);
	$desc = sanitize($_POST["description"]);
	$type = sanitize($_POST["dishtype"]);
	$ingredients = $_POST["ingredient"];
	
	$params = array($name, $price, $desc, $type, $id);
	$bindings = buildBindings($params);
	
	$param2 = array_keys($arr[0]);
	$queryBase = "UPDATE dish";
	
	$finalQuery = createQuery($queryBase, "UPDATE", $param2, array("DishID"));
	$res = mysqli_prepared_query_mod($connection, $finalQuery, $bindings, $params);
	
	$delete = "DELETE FROM ".$_tblingrli." WHERE `DishID` = ".$id;
	
	$qres = mysqli_prepared_query_mod($connection, $delete, FALSE, FALSE);
	
	if ($qres) {
		echo "CLEARED SHIT.<br />";
		
		$queryTags = "INSERT INTO ".$_tblingrli." (`IngredientID`, `DishID`) VALUES (?, ?)";
	
		foreach($ingredients as $in) {
			$paramx2 = array($in, $id);
			$binding2s = buildBindings($paramx2);
			$res2 = mysqli_prepared_query_insert($connection, $queryTags, $binding2s, $paramx2);
		}	
	}
	
	if ($res) {
		echo $id ." Sucessfully Updated";	
	} else {
		echo "Error.";	
	}
	echo "<a href='".$_SERVER["PHP_SELF"]."'>Go Back</a>";
	
} else if (isset($_GET["edit"])) {
	
	//$query = mysqli_prepared_query($connection, $stmt_select_all_dish_restrict, "i", array($_REQUEST["edit"]));
	$json = file_get_contents($GLOBALS["API_PATH"]."/dish/id/".$_GET["edit"]);
	$arr = json_decode($json, true);
?>
<form action="?edited=true" id="dish_edit_form" method="post">
<input type="hidden" name="id" value="<?php echo $arr[0]['DishID'] ?>" />
<input type="text" name="dishname" value="<?php echo $arr[0]['DishName'] ?>" /><br />
<!-- PUT TINGREDIENT SELECTIONS HERE -->
<?php 
	$json2 = file_get_contents($GLOBALS["API_PATH"]."/ingredient");
	$arr2 = json_decode($json2, true);
	$json3 = file_get_contents($GLOBALS["API_PATH"]."/ingredient_list/dish/".$arr[0]['DishID']);
	$arr3 = json_decode($json3, true);
	$skip = 0;
	foreach($arr2 as $row) {
		
		foreach($arr3 as $row2) {
			if ($row["IngredientID"] == $row2["IngredientID"]) {
				echo '
<input type="checkbox" checked="true" name="ingredient[]" value="'.$row["IngredientID"].'" /> '.$row["IngredientName"].'('.$row["IngredientType"].') ';
				$skip = 1;
				break;
			}
		}
		if ($skip == 1) {
			$skip = 0;	
		} else {
			echo '
<input type="checkbox" name="ingredient[]" value="'.$row["IngredientID"].'" /> '.$row["IngredientName"].'('.$row["IngredientType"].') ';
		}
		
	}
?><br />
<input type="text" name="price" value="<?php echo $arr[0]['PriceID'] ?>" /><br />
<input type="textarea" name="description" value="<?php echo $arr[0]['Description'] ?>" /><br />
<input type="text" name="dishtype" value="<?php echo $arr[0]['DishType'] ?>" /><br />
<input type="submit" />
</form>

<?php
echo "<a href='".$_SERVER["PHP_SELF"]."'>Go Back</a>";
} else {
	echo "Nothing Selected To Edit;<br />";	
	$json = file_get_contents($GLOBALS["API_PATH"]."/dish");
	$arr = json_decode($json, true);
	
	echo "<h1>Dishes</h1>";
	
	foreach ($arr as $row) {
		echo '<a href="?edit='.$row["DishID"].'">'.$row["DishName"].'</a> [<a href="'.$_SERVER["PHP_SELF"].'?delete='.$row["DishID"].'">X</a>]<br />';	
	}
	echo "<br /> <br />";
	echo "<a href='".$_SERVER["PHP_SELF"]."?add=form'>Add A New Dish</a>";
}


echo "<br /><a href='index.php'>Return To Admin Panel.</a>";
$connection->close();

?>
