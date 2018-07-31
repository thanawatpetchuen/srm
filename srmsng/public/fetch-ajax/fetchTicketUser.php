<?php
// For Testing
// $customer_no = $_GET['account_no'];

session_start();
$customer_no = $_SESSION['account_no'];


$statement_after_from = "srm_request WHERE request_user != '' AND request_id = '$customer_no'" ;
 
// Table's primary key
$primaryKey = 'sng_code';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected Columns
$columns = array(
    array( 'db' => 'sng_code',       'dt' => 0 ),
    array( 'db' => 'problem_type',   'dt' => 1 ),
    array( 'db' => 'asset_problem',  'dt' => 2 ),
    array( 'db' => 'request_time',   'dt' => 3 ),
    array( 'db' => 'close_time',     'dt' => 4 ),
    array( 'db' => 'request_user',   'dt' => 5 ),
    array( 'db' => 'job_status',     'dt' => 6 )
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
