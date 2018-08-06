<?php
 
$statement_after_from = 'system_log ORDER BY date_time ASC';
 
// Table's primary key
$primaryKey = 'no';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "no", 'dt' => 0),
    array( 'db' => 'account_no', 'dt' => 1 ),
    array( 'db' => 'user',  'dt' => 2 ),
    array( 'db' => 'level',   'dt' => 3 ),
    array( 'db' => 'action',     'dt' => 4 ),
    array( 'db' => 'date_time',    'dt' => 5),
);

 
// SQL server connection information
$sql_details = array(
    'user' => 'admin',
    'pass' => '1234',
    'db'   => 'sngbase',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp2.class.php' );
 
echo json_encode(
    SSP::complex( $_GET, $sql_details, $statement_after_from, $primaryKey, $columns )
);
?>