<?php



// Tables

$_tblcust = 'customer';
$_tbldish = 'dish';
$_tblingrli = 'ingredientlist';
$_tblingr = 'ingredients';
$_tblordets = 'orderdetails';
$_tblorder = 'orders';
$_tblprice = 'price';
$_tbltest = 'test';

$_tblcart = "cart";
$_tblcartdet = "cartdetails";

// SELECTS
$stmt_select_all_customer = 'SELECT * FROM ' . $_tblcust;
$stmt_select_all_dish = 'SELECT * FROM ' . $_tbldish;
$stmt_select_all_ingredients = 'SELECT * FROM ' . $_tblingr;
$stmt_select_all_ingredients_list = 'SELECT * FROM ' . $_tblingrli;
$stmt_select_all_orderdets = 'SELECT * FROM ' . $_tblordets;
$stmt_select_all_orders = 'SELECT * FROM ' . $_tblorder;
$stmt_select_all_prices = 'SELECT * FROM ' . $_tblprice;
$stmt_select_all_cart = 'SELECT * FROM ' . $_tblcart;
$stmt_select_all_cartdet = 'SELECT * FROM ' . $_tblcartdet;


?>
