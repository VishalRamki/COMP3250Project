# API

```
/api/order/time/all_before_today - Returns All the orders which took place before TODAY.

/api/order/time/all_before_today/pickup/0 - Returns All The Orders which took place before TODAY and has a PICKUP value of 0.

/api/order/time/all_before_today/pickup/0/sort/id/desc/limit/2 - Returns two entries in the table sorted by the OrderID in descending order, where orders occured before today, with pick value 0.
```

The time function on the order table can be daisy-chained with any of the resources from the table.

```
/api/customer/ - Returns All Data from the Customer Table
/api/{TABLE}/ 
```
```
/api/customer/sort/id/desc
/api/cart/sort/time/desc/limit/1
/api/cart/customer/25567/sort/time/desc/limit/1
```
```
/api/guest: {

    onSucess: Returns the ID of A Temporary Guest.

}
```
```
/api/post/cart: {

    Required Fields : {
        "CustomerID"
    },
    onSuccess: Returns The CartID,
    onFailure: Returns 1;

}
```
```
/api/post/cart_details: {

    Required Fields: {
        "CartID",
        "DishID",
        "Quantity"
    },
    onSucess: Returns 0,
    onFailure: Returns 1;

}
```

# API Use:

For Just Calling Data
```
/api/{resource}/{resource_identifier}/{value}
```

Performing Functions On The Recordset
```
/api/{resource}/{database_function}/{resource_identifer_on_which_to_sort}/{sort_by}

/api/{resource}/{resource_identifier}/{value}/{database_function}/{resource_identifer_on_which_to_sort}/{sort_by}

/api/{resource}/{resource_identifier}/{value_equality}/{value}/{database_function}/{resource_identifer_on_which_to_sort}/{sort_by}
```

This api call will return all rows with the `{Value}` inside the `{resource_indentifier}` from the `{resource}` tables in json format.

# Available Calls

### Resource: "Customer"
```
Resource Identifier: {
    "ID"
    "NAME"
    "NUMBER"
    "ADDRESS"
    "EMAIL"
    "VICINITY"
}
```
### Resource: "Dish" 
```
Resource Identifier: {
    "ID",
	"NAME",
	"TYPE"
}
```
### Resource: "Order"
```
Resource Identifier: {
    "ID",
    "CUSTOMER",
    "PAID",
    "PICKUP",
    "DELIVERY"
}
```

### Database Functions

Function: SORT

```
Function Parameters: {
    {resource},
    ASC or DESC
}
```

Function: LIMIT

```
Function Parameters: {
    {integer}
}
```

Function {value_equality}

```
Function Parameters: {
    bigger or smaller,
    {value}
}
```
