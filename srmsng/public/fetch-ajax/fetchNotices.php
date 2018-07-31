<?php
$statement_after_from = "notice ORDER BY id DESC";
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "id", 'dt' => 0),
    array( 'db' => "title", 'dt' => 1),
    array( 'db' => "DATE_FORMAT(date, '%d %M %Y (%H:%i)')", 'dt' => 2 ),
    array( 'db' => 'type',  'dt' => 3 ),
    // array( 'db' => 'last_login',   'dt' => 6 ),
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