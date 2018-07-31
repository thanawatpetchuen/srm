<?php

$statement_after_from = "fse WHERE fse_code != 0 ORDER BY engname ASC"; 

// Table's primary key
$primaryKey = 'fse_code';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "fse_code", 'dt' => 0),
    array( 'db' => 'thainame', 'dt' => 1 ),
    array( 'db' => 'engname', 'dt' => 2 ),
    array( 'db' => 'abbr', 'dt' => 3 ),
    array( 'db' => 'company', 'dt' => 4 ),
    array( 'db' => 'position', 'dt' => 5 ),
    array( 'db' => 'service_center',   'dt' => 6 ),
    array( 'db' => 'section', 'dt' => 7 ),
    array( 'db' => 'team',     'dt' => 8 ),
    array( 'db' => 'status',  'dt' => 9 ),
    array( 'db' => 'email',  'dt' => 10 ),
    array( 'db' => 'phone',  'dt' => 11 ),
    array( 'db' => 'username',  'dt' => 12 ),
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