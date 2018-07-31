<?php
 
$statement_after_from = 'customers ORDER BY customer_eng_name ASC'; 

// Table's primary key
$primaryKey = 'customer_no';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => 'customers.customer_no', 'dt' => 0 ),
    array( 'db' => 'customer_name', 'dt' => 1 ),
    array( 'db' => 'customer_eng_name', 'dt' => 2 ),
    array( 'db' => 'account_group', 'dt' => 3 ),
    array( 'db' => 'sale_team', 'dt' => 4 ),
    array( 'db' => 'product_sale',   'dt' => 5 ),
    array( 'db' => 'service_sale', 'dt' => 6 ),
    array( 'db' => 'taxid',     'dt' => 7 ),
    array( 'db' => 'primary_contact',  'dt' => 8 ),
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