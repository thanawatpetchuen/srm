<?php

$statement_after_from = 'recover_msg ORDER BY date_time DESC';
 
// Table's primary key
$primaryKey = 'no';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => "no",        'dt' => 0 ),
    array( 'db' => "username",  'dt' => 1 ),
    array( 'db' => 'email',     'dt' => 2 ),
    array( 'db' => 'message',   'dt' => 3 ),
    array( 'db' => 'status',    'dt' => 4 ),
    array( 'db' => 'date_time', 'dt' => 5 ),
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