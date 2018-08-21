<?php

// SQL statement for "fetchTicket.php" is

/*
SELECT sale_order_no, date_order, shipment_date, sale_person, contact_name, contact_number, customer_name, sitename
FROM (
        SELECT sale_order_no, date_order, shipment_date, sale_person, contact_name, contact_number, customer_name, sitename
        FROM sale_order
            INNER JOIN location
                ON sale_order.location_code  =  location.location_code
            INNER JOIN customers
                ON location.customer_no  =  customers.customer_no
     ) 
AS sub_q 
*/

$statement_after_for = "(SELECT sale_order_no, date_order, shipment_date, sale_person, contact_name, contact_number, customer_name, sitename
                         FROM sale_order
                            INNER JOIN location
                                ON sale_order.location_code  =  location.location_code
                            INNER JOIN customers
                                ON location.customer_no  =  customers.customer_no
                        ) 
                        AS sub_q ";

// Table's primary key
$primaryKey = 'sale_order_no';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "sale_order_no",          'dt' => 0),
    array( 'db' => 'date_order',             'dt' => 1 ),
    array( 'db' => 'shipment_date',          'dt' => 2 ),
    array( 'db' => 'sale_person',            'dt' => 3 ),
    array( 'db' => 'contact_name',           'dt' => 4 ),
    array( 'db' => 'contact_number',         'dt' => 5 ),
    array( 'db' => 'customer_name',          'dt' => 6 ),
    array( 'db' => 'sitename',               'dt' => 7 ),
);

// SQL server connection information
$sql_details = array(
    'user' => 'admin',
    'pass' => '1234',
    'db'   => 'sngbase',
    'host' => 'localhost'
);

require( 'ssp3.class.php' );

echo json_encode(
    SSP::mod( $_GET, $sql_details, $statement_after_for, $primaryKey, $columns)
);
?>