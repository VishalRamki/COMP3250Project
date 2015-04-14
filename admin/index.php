

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
<script src="design/js/jquery.min.js" type="text/javascript"></script>
<script src="design/menu/script.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="design/css/styles.css">
<link rel="stylesheet" type="text/css" media="all" href="design/css/switchery.min.css">
<script type="text/javascript" src="design/js/switchery.min.js"></script>
<title>Cuisine Core - Admin Panel - Dashboard</title>
</head>

<body>
<div id="menu" class="cf" >

	<?php include("links.php"); ?>
    
</div>

<div id="body">
	<div class="content">
    	<div id="boxes" class="cf">
        	<div class="fullBox">
        		<h1 class="pageHeader">Dashboard</h1>
            </div>
        </div>
        
        <div id="boxes" class="cf">
        	<div id="box" class="box-one">
            <?php
				$json_picked = file_get_contents($GLOBALS["API_PATH"]."/order/time/all_before_today/pickup/0");
				$json_not_picked = file_get_contents($GLOBALS["API_PATH"]."/order/time/all_before_today/pickup/1");
				$arr = json_decode($json_picked, true);
				$arr2 = json_decode($json_not_picked, true);
			
			?>
            	<div id="boxHeader">Quick Stats:</div>
                <div id="stats">
                
                	<ol>
                    	<li><span><?php echo count($arr); ?></span><p>Fullfilled Orders For Today.</p></li>
                        <li><span><?php echo count($arr2); ?></span><p>Unfullfilled Orders For Today.</p></li>
                        <li><span>$1,040</span><p>Total Funds Accrued.</p></li>
                    </ol> 
                </div>
            </div>
            
            <div id="box" class="box-two">
            	<div id="boxHeader">Recent FullFilled Orders:</div>
                <div id="stats">
                
                	<ol id="fun">
                    </ol> 
                </div>
            </div>
            
            <div id="box" class="box-one">
            	<div id="boxHeader">Recent UnFullFilled Orders:</div>
                <div id="stats">
                
                	<ol id="un">
                    </ol>
                </div>
            </div>
            
        </div>
        <div id="boxes">
        	<div id="one-two-chart">
            	<canvas id="chartOne" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(function() {
	
	var un = -1, fun = -1;
	
	function callPickUp() {
		setTimeout(function() {
			$.ajax({ url: "http://localhost/COMP3250/api/order/time/all_before_today/pickup/0/sort/id/desc/limit/3", success: function(data) {
				console.log(data);
				$.each(data, function(key, val) {
					$("#fun").append("<li><p class='c'><a href='order.php?view=true&order="+val['OrderID']+"'>Order ID: "+val['OrderID']+"</p></li>");
				});
			}, dataType: "json", complete: callPickUp });
		}, 3000);
	}
	
	//callPickUp();
	function callPickUp2() {
		setTimeout(function() {
		$.ajax({ url: "http://localhost/COMP3250/api/order/time/all_before_today/pickup/1/sort/id/desc/limit/3", success: function(data) {
			console.log(data);
			$.each(data, function(key, val) {
				$("#un").append("<li><p class='c'><a href='order.php?view=true&order="+val['OrderID']+"'>Order ID: "+val['OrderID']+"</p></li>");
			});
			}, dataType: "json", complete: callPickUp2 });
		}, 3000);
	}
});

</script>
<?php include("js.php") ?>
</body>
</html>
<?php $connection->close(); ?>
