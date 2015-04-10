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
