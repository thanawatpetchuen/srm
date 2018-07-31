<?php
 
$statement_after_from = 'material_master_record ORDER BY model ASC'; 


// Table's primary key
$primaryKey = 'itemnumber';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "itemnumber", 'dt' => 0),
    array( 'db' => 'model', 'dt' => 1 ),
    array( 'db' => 'power',     'dt' => 2 ),
    array( 'db' => 'item_class', 'dt' => 3 ),
    array( 'db' => 'category', 'dt' => 4 ),
    array( 'db' => 'is_lot', 'dt' => 5 ),
    array( 'db' => 'is_serial', 'dt' => 6 ),
    array( 'db' => 'is_warranty',   'dt' => 7 ),
    array( 'db' => 'created_on', 'dt' => 8 ),
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'admin',
    'pass' => '1234',
    'db'   => 'sngbase',
    'host' => 'localhost'
);
 
require( 'ssp2.class.php' );
 
echo json_encode(
    SSP::complex( $_GET, $sql_details, $statement_after_from, $primaryKey, $columns)
);
?>