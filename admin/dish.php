

<?php

include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.
$connection = new mysqli($server, $user, $pass, $dbname);

if($connection->connect_errno > 0){
    die('Unable to connect to database [' . $connection->connect_error . ']');
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="design/menu/styles.css">
<link rel="stylesheet" href="design/base_style.css">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="design/menu/script.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="design/css/styles.css">
<link rel="stylesheet" type="text/css" media="all" href="design/css/switchery.min.css">
<script type="text/javascript" src="design/js/switchery.min.js"></script>
<title>Cuisine Core - Admin Panel</title>
</head>

<body>
<div id="menu" class="cf" >

	<?php include("links.php"); ?>
    
</div>

<div id="body">
	<div class="content">
    
    <?php if (isset($_GET["view"]) && strtoupper($_GET["view"]) == "TRUE") { ?>
    	<div id="boxes" class="cf">
        	<h1>Viewing Dishes</h1>
        </div>
        
        <div id="boxes" class="cf" style="text-align:center">
        <div style="text-align: center;" >
        	[<a href="<?php echo $_SERVER["PHP_SELF"]; ?>" class="inline-link-1">Add Dishes</a>]</h4>
        </div>
        
        <?php
			if (!isset($_GET["order"])) {
				$json = file_get_contents($GLOBALS["API_PATH"]."/dish");	
			} else {
				$json = file_get_contents($GLOBALS["API_PATH"]."/dish/sort/".$_GET["order"]);
			}
			
			$arr = json_decode($json, true);
		?>
<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->
	<tr><th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?view=true&order=name">Dish Name</a></th><th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?view=true&order=price">Price</a></th><th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?view=true&order=description">Description</a></th><th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?view=true&order=type">Dish Type</a></th><th>Ingredients</th><th>Delete</th></tr><!-- Table Header -->
    
    <?php foreach($arr as $row) { ?>
	<tr><td><?php echo $row["DishName"]; ?></td><td><?php echo $row["PriceID"]; ?></td><td><?php echo $row["Description"]; ?></td><td><?php echo $row["DishType"]; ?></td>
    
    <td>
		<?php
			$json2 = file_get_contents($GLOBALS["API_PATH"]."/ingredient_list/dish/".$row["DishID"]);
			$arr2 = json_decode($json2, true);
			$last = -1;
			if (!empty($arr2)) {
				
				foreach($arr2 as $rowx) {
					$json3 = file_get_contents($GLOBALS["API_PATH"]."/ingredient/id/".$rowx["IngredientID"]);
					$arr3 = json_decode($json3, true);
					if (!empty($arr3)){
						echo $arr3[0]["IngredientName"]." ";
					}
				}
			}
			
		?>
	</td>
    
    <td><a href="<?php echo $GLOBALS["API_PATH"]; ?>/drop/dish/id/<?php echo $row["DishID"]; ?>">X</a></td></tr><!-- Table Row -->
    <?php } ?>
</table>
		</div>
    
    <?php } ?>
    <?php if (!isset($_GET["view"])) { ?>
    	<div id="boxes" class="cf">
        	<h1>Add Dish</h1>
        </div>
        
        <div id="boxes" class="cf">
  <div id="wrapper">  
  <form id="dish_adder" method="post">
  <div class="col-2">
    <label>
      Dish Name
      <input placeholder="Name Of Your Dish" id="dishname" name="DishName" tabindex="1">
    </label>
  </div>
  <div class="col-2">
    <label>
      Set Price
      <input placeholder="Price Of Dish" id="price" name="PriceID" tabindex="2">
    </label>
  </div>
  
  <div class="col-2">
    <label>
      Dish Type
      <input placeholder="Type Of Dish" id="dishtype" name="DishType" tabindex="3">
    </label>
  </div>
  <div class="col-2">
    <label>
      Description Of Dish
      <input placeholder="Description Of Dish" id="description" name="Description" tabindex="3">
    </label>
  </div>
  
  <?php
	$json = file_get_contents($GLOBALS["API_PATH"]."/ingredient");
	$arr = json_decode($json, true);
	foreach ($arr as $row) {
?>
  <div class="col-4">
    <label><?php echo $row["IngredientName"]; ?> (<?php echo $row["IngredientType"]; ?>)</label>
    <center style="position:relative;margin-bottom:8px;"><input type="checkbox" id="ingredient[]" name="ingredient[]" value="<?php echo $row["IngredientID"]; ?>" class="js-switch"></center>
  </div>
<?php } ?>
  <div class="col-submit">
    <button class="submitbtn">Submit Form</button>
  </div>
  
  </form>
  </div>
<script type="text/javascript">
var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

elems.forEach(function(html) {
  var switchery = new Switchery(html);
});

$(function() { //shorthand document.ready function
	
    $('#dish_adder').on('submit', function(e) { //use on if jQuery 1.7+
        e.preventDefault();  //prevent form from submitting
        var data = $("#dish_adder :input").serializeArray();
        console.log(data); //use the console for debugging, F12 in Chrome, not alerts
		
		$.post('/COMP3250/api/post/dish', data, function(response) {
			if (response == 0) {
				$('#wrapper').prepend('<div id="boxes" class="cf"><div class="green-alert">Added Sucessfully.</div></div> ');
				$(".green-alert").hide();
				$(".green-alert").show()
			} else {
				$('#wrapper').prepend('<div id="boxes" class="cf"><div class="red-alert">Error. Retry.</div></div> ');
				$(".red-alert").hide();
				$(".red-alert").show()	
			}
			console.log("Response: "+response);
		});
    });
	
});
</script>
        </div>
        
        <?php } ?>
    </div>
</div>
<?php include("js.php") ?>
</body>
</html>
<?php $connection->close(); ?>
