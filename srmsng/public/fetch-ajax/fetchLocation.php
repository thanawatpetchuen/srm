<?php
$customer_no = $_GET['id'];
$statement_after_from = "location WHERE location.customer_no = '$customer_no' 
                ORDER BY sitename ASC";
 
// Table's primary key
$primaryKey = 'location_code';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "location_code", 'dt' => 0),
    array( 'db' => 'customer_no', 'dt' => 1 ),
    array( 'db' => 'sitename',     'dt' => 2 ),
    array( 'db' => "house_no", 'dt' => 3),
    array( 'db' => 'village_no',     'dt' => 4 ),
    array( 'db' => 'soi',     'dt' => 5 ),
    array( 'db' => 'road',     'dt' => 6 ),
    array( 'db' => 'district',     'dt' => 7 ),
    array( 'db' => 'sub_district',     'dt' => 8 ),
    array( 'db' => 'province',     'dt' => 9 ),
    array( 'db' => 'postal_code',     'dt' => 10 ),
    array( 'db' => 'region',     'dt' => 11 ),
    array( 'db' => 'country',     'dt' => 12 ),
    array( 'db' => 'store_phone',     'dt' => 13 ),
    array( 'db' => 'latitude',     'dt' => 14 ),
    array( 'db' => 'longitude',     'dt' => 15 ),
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
    SSP::complex( $_GET, $sql_details, $statement_after_from, $primaryKey, $columns)
);
?>