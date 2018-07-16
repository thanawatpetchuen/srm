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

// DB table to use
// $table = 'asset_tracker a JOIN customers b ON a.customer_no = b.customer_no JOIN location c ON a.location_code = c.location_code JOIN sale_order d ON a.sale_order_no = d.sale_order_no JOIN material_master_record e ON a.itemnumber = e.itemnumber JOIN fse f ON a.fse_code = f.fse_code';
$table = 'asset_tracker, location, sale_order, customers, fse, material_master_record WHERE asset_tracker.fse_code = fse.fse_code AND asset_tracker.customer_no = customers.customer_no AND asset_tracker.location_code = location.location_code AND sale_order.sale_order_no = asset_tracker.sale_order_no AND asset_tracker.itemnumber = material_master_record.itemnumber';
// Table's primary key

$primaryKey = 'no';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
    array( 'db' => "sng_code", 'dt' => 0),
    array( 'db' => "asset_tracker.sale_order_no", 'dt' => 1),
    array( 'db' => 'since', 'dt' => 2 ),
    array( 'db' => 'date_order',  'dt' => 3 ),
    array( 'db' => 'po_number',   'dt' => 4 ),
    array( 'db' => 'po_date',     'dt' => 5 ),
    array( 'db' => 'customer_name',     'dt' => 6 ),
    array( 'db' => 'model',     'dt' => 7 ),
    array( 'db' => "serial", 'dt' => 8),
    array( 'db' => 'engname', 'dt' => 9 ),
    array( 'db' => 'do_number',  'dt' => 10 ),
    array( 'db' => 'battery',   'dt' => 11 ),
    array( 'db' => 'quantity',     'dt' => 12 ),
    array( 'db' => 'battery_date',     'dt' => 13 ),
    array( 'db' => 'sitename',     'dt' => 14 ),
    array( 'db' => 'house_no',     'dt' => 15 ),
    array( 'db' => 'village_no',     'dt' => 16 ),
    array( 'db' => 'road',     'dt' => 17 ),
    array( 'db' => 'sub_district',     'dt' => 18 ),
    array( 'db' => 'district',     'dt' => 19 ),
    array( 'db' => 'province',     'dt' => 20 ),
    array( 'db' => 'postal_code',     'dt' => 21 ),
    array( 'db' => 'region',     'dt' => 22 ),
    array( 'db' => 'country',     'dt' => 23 ),
    array( 'db' => 'contactname',     'dt' => 24 ),
    array( 'db' => 'contactnumber',     'dt' => 25 ),
    array( 'db' => 'startwarranty',     'dt' => 26 ),
    array( 'db' => 'endwarranty',     'dt' => 27 ),
    array( 'db' => 'ups_status',     'dt' => 28 ),
    array( 'db' => 'pmyear',     'dt' => 29 ),
    array( 'db' => 'nextpm',     'dt' => 30 ),
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
/*, '
    asset_tracker.fse_code = fse.fse_code
    AND asset_tracker.customer_no = customers.customer_no
    AND asset_tracker.location_code = location.location_code 
    AND sale_order.sale_order_no = asset_tracker.sale_order_no
    AND asset_tracker.itemnumber = material_master_record.itemnumber
    '*/
?>
