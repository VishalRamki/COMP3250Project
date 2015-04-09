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
	$ingredient = sanitize($_POST["ingredient"]);
	$price = sanitize($_POST["price"]);
	$desc = sanitize($_POST["description"]);
	$type = sanitize($_POST["dishtype"]);
	
	$params = array($name, $ingredient, $price, $desc, $type);
	$bindings = buildBindings($params);

	$queryBase = "INSERT INTO dish (DishName, IngredientID, PriceID, Description, DishType) VALUES(?, ?, ?, ?, ?)";
	
	$res = mysqli_prepared_query_insert($connection, $queryBase, $bindings, $params);
	
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
<input type="text" name="ingredient" value="" /><br />
<input type="text" name="price" value="" /><br />
<input type="textarea" name="description" cols="20" rows="10" value="" /><br />
<input type="text" name="dishtype" value="" /><br />
<input type="submit" />
</form>
<?php
} else if (isset($_GET["delete"])) {
	
	$deleteId = sanitize($_GET["delete"]);
	
	$query = "DELETE FROM ".$_tbldish." WHERE DishId = ?";
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
	echo "[".sanitize($_POST["ingredient"])."]<br />";
	echo "[".sanitize($_POST["price"])."]<br />";
	echo "[".sanitize($_POST["description"])."]<br />";
	echo "[".sanitize($_POST["dishtype"])."]<br />";
	
	$json = file_get_contents($_APIPATH."/api/dish");
	$arr = json_decode($json, true);
	
	$id = sanitize($_POST["id"]);
	$name = sanitize($_POST["dishname"]);
	$ingredient = sanitize($_POST["ingredient"]);
	$price = sanitize($_POST["price"]);
	$desc = sanitize($_POST["description"]);
	$type = sanitize($_POST["dishtype"]);
	
	$params = array($name, $ingredient, $price, $desc, $type, $id);
	$bindings = buildBindings($params);
	
	$param2 = array_keys($arr[0]);
	$queryBase = "UPDATE dish";
	
	$finalQuery = createQuery($queryBase, "UPDATE", $param2, array("DishID"));
	$res = mysqli_prepared_query_mod($connection, $finalQuery, $bindings, $params);
	
	if ($res) {
		echo $id ." Sucessfully Updated";	
	} else {
		echo "Error.";	
	}
	echo "<a href='".$_SERVER["PHP_SELF"]."'>Go Back</a>";
	
} else if (isset($_GET["edit"])) {
	
	//$query = mysqli_prepared_query($connection, $stmt_select_all_dish_restrict, "i", array($_REQUEST["edit"]));
	$json = file_get_contents($_APIPATH."/api/dish/id/".$_GET["edit"]);
	$arr = json_decode($json, true);
?>
<form action="?edited=true" id="dish_edit_form" method="post">
<input type="hidden" name="id" value="<?php echo $arr[0]['DishID'] ?>" />
<input type="text" name="dishname" value="<?php echo $arr[0]['DishName'] ?>" /><br />
<input type="text" name="ingredient" value="<?php echo $arr[0]['IngredientID'] ?>" /><br />
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
