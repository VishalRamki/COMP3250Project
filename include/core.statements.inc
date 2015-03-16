<?php


// Statements

// SELECTS
$stmt_select_all_customer = 'SELECT * FROM ' . $_tblcust;
$stmt_select_all_restrict_customer = 'SELECT * FROM ' . $_tblcust . ' WHERE ? = ?';

// INSERTS
$stmt_insert_into_customer = 'INSERT INTO ' . $_tblcust . '(CustomerID, CustomerName, PhoneNumber, Address, Email, Vicinity) VALUES (?, ?, ?, ?, ?, ?)';


?>
