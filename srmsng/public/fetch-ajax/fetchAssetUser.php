<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
session_start();
// DB table to use
$customer_no = $_SESSION['account_no'];
$table = 'asset_tracker, sale_order, material_master_record, location
WHERE asset_tracker.customer_no = ' . $customer_no . ' 
AND asset_tracker.sale_order_no = sale_order.sale_order_no
AND asset_tracker.itemnumber = material_master_record.itemnumber
AND asset_tracker.location_code = location.location_code';
 
// Table's primary key
$primaryKey = 'sng_code';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => "sng_code", 'dt' => 0),
    array( 'db' => 'since', 'dt' => 1 ),
    array( 'db' => 'model',     'dt' => 2 ),
    array( 'db' => 'power',     'dt' => 3 ),
    array( 'db' => 'battery',     'dt' => 4 ),
    array( 'db' => 'quantity',     'dt' => 5 ),
    array( 'db' => "serial", 'dt' => 6),
    array( 'db' => 'sitename',     'dt' => 7 ),
    array( 'db' => 'startwarranty',     'dt' => 8 ),
    array( 'db' => 'endwarranty',     'dt' => 9 ),
    array( 'db' => 'ups_status',     'dt' => 10 ),
    array( 'db' => 'typeofcontract',     'dt' => 11 ),
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
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
);
?>