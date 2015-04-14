

<?php

include('../include/core.db.inc'); // Holds Information Relating to the database
include('../include/core.statements.inc'); // PHP mySQL Prepared Statments
include('../include/core.db.funcs.inc'); // Reusable mysql functions.
$connection = new mysqli($server, $user, $pass, $dbname);

if($connection->connect_errno > 0){
    die('Unable to connect to database [' . $connection->connect_error . ']');
}
$manage = 0;
if (isset($_GET["view"]) && strtoupper($_GET["view"]) == "TRUE") {
	$manage = 1;	
} else {
	$manage = 0;	
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
<title>Cuisine Core - Admin Panel - Add Ingredient</title>
</head>

<body>
<div id="menu" class="cf" >
<?php include("links.php"); ?>
</div>

<div id="body">
	<div class="content">
    
    <?php if (isset($_GET["view"]) && strtoupper($_GET["view"]) == "TRUE") { ?>
    
    	<div id="boxes" class="cf">
        	<h1>Viewing Ingredient</h1>
        </div>
        
        <div id="boxes" class="cf" style="text-align:center">
        <div style="text-align: center;" >
        	[<a href="<?php echo $_SERVER["PHP_SELF"]; ?>" class="inline-link-1">Add Ingredient</a>]</h4>
        </div>
        <?php
			if (!isset($_GET["order"])) {
				$json = file_get_contents($GLOBALS["API_PATH"]."/ingredient");	
			} else {
				$json = file_get_contents($GLOBALS["API_PATH"]."/ingredient/sort/".$_GET["order"]);
			}
			$arr = json_decode($json, true);
		?>
<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->
	<tr><th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?view=true&order=name">Ingredient Name</a></th><th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?view=true&order=type">Ingredient Type</a></th><th>Delete</th></tr><!-- Table Header -->
    
    <?php foreach($arr as $row) { ?>
	<tr><td><?php echo $row["IngredientName"]; ?></td><td><?php echo $row["IngredientType"]; ?></td><td><a href="<?php echo $_SERVER["PHP_SELF"] ?>?delete=<?php echo $row["IngredientID"]; ?>">X</a></td></tr><!-- Table Row -->
    <?php } ?>
</table>

	</div>
   
    <?php } ?>
    
    <?php if (!isset($_GET["view"])) { ?>
    	<div id="boxes" class="cf">
        	<h1>Add Ingredient</h1>
        </div>
        
        <div id="boxes" class="cf">
  <div id="wrapper">  
  <form id="dish_adder" method="post">
  <div class="col-2">
    <label>
      Ingredient Name
      <input placeholder="Name Of Your Ingredient" id="IngredientName" name="IngredientName" tabindex="1">
    </label>
  </div>
  <div class="col-2">
    <label>
      Type
      <input placeholder="Type Of Your Ingredient" id="IngredientType" name="IngredientType" tabindex="2">
    </label>
  </div>
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
		
		$.post('/COMP3250/api/post/ingredient', data, function(response) {
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
