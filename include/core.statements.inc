<?php


// Statements
$_tblcust = 'customer';
$_tbldish = 'dish';
$_tblingrli = 'ingredient list';
$_tblordets = 'orderdetails';
$_tblorder = 'orders';
$_tblprice = 'price';

// SELECTS
$stmt_select_all_customer = 'SELECT * FROM ' . $_tblcust;
$stmt_select_all_dish = 'SELECT * FROM ' . $_tbldish;
$stmt_select_all_ingredients = 'SELECT * FROM ' . $_tblingrli;
$stmt_select_all_orderdets = 'SELECT * FROM ' . $_tblordets;
$stmt_select_all_orders = 'SELECT * FROM ' . $_tblorder;
$stmt_select_all_prices = 'SELECT * FROM ' . $_tblprice;


// INSERTS
$stmt_insert_into_customer = 'INSERT INTO ' . $_tblcust . '(CustomerID, CustomerName, PhoneNumber, Address, Email, Vicinity) VALUES (?, ?, ?, ?, ?, ?)';

?>
