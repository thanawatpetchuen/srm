<?php

// SQL statement for "fetchTicket.php" is

/*
SELECT model, quantity, unit_price, amount
FROM (
        SELECT model, quantity, unit_price, quantity * unit_price AS amount
        FROM sale_order, sale_order_item, material_master_record
        WHERE sale_order.sale_order_no = '2018-SU023'
            AND sale_order_item.sale_order_no = '2018-SU023'
            AND sale_order_item.itemnumber    = material_master_record.itemnumber        
     ) 
AS sub_q 
*/

$sale_order_no = $_GET['sale_order_no'];

$statement_after_for = "(SELECT model, quantity, unit_price, quantity * unit_price AS amount
                         FROM sale_order, sale_order_item, material_master_record
                         WHERE sale_order.sale_order_no = 'sale_order_no'
                            AND sale_order_item.sale_order_no = 'sale_order_no'
                            AND sale_order_item.itemnumber    = material_master_record.itemnumber        
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
    array( 'db' => "model",          'dt' => 0),
    array( 'db' => 'quantity',             'dt' => 1 ),
    array( 'db' => 'unit_price',          'dt' => 2 ),
    array( 'db' => 'amount',            'dt' => 3 )
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