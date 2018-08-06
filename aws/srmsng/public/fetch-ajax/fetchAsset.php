<?php

$statement_after_from = 'asset_tracker, location, sale_order, customers, fse, material_master_record 
    WHERE asset_tracker.fse_code = fse.fse_code 
        AND asset_tracker.customer_no = customers.customer_no 
        AND asset_tracker.location_code = location.location_code 
        AND sale_order.sale_order_no = asset_tracker.sale_order_no 
        AND asset_tracker.itemnumber = material_master_record.itemnumber
    ORDER BY customer_name ASC';

// Table's primary key
$primaryKey = 'sng_code';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// Selected columns
$columns = array(
    array( 'db' => "sng_code", 'dt' => 0),
    array( 'db' => "asset_tracker.sale_order_no", 'dt' => 1),
    array( 'db' => 'since', 'dt' => 2 ),
    array( 'db' => 'date_order',  'dt' => 3 ),
    array( 'db' => 'po_number',   'dt' => 4 ),
    array( 'db' => 'po_date',     'dt' => 5 ),
    array( 'db' => 'do_number',     'dt' => 6 ),
    array( 'db' => 'customer_name',     'dt' => 7 ),
    array( 'db' => 'sale_team',     'dt' => 8 ),
    array( 'db' => 'product_sale',     'dt' => 9 ),
    array( 'db' => 'service_sale',     'dt' => 10 ),
    array( 'db' => 'model',     'dt' => 11 ),
    array( 'db' => "serial", 'dt' => 12),
    array( 'db' => 'power',     'dt' => 13 ),
    array( 'db' => 'engname', 'dt' => 14 ),
    array( 'db' => 'battery',   'dt' => 15 ),
    array( 'db' => 'quantity',     'dt' => 16 ),
    array( 'db' => 'battery_date',     'dt' => 17 ),
    array( 'db' => 'sitename',     'dt' => 18 ),
    array( 'db' => 'asset_tracker.location_code',     'dt' => 19 ),
    array( 'db' => 'house_no',     'dt' => 20 ),
    array( 'db' => 'village_no',     'dt' => 21 ),
    array( 'db' => 'road',     'dt' => 22 ),
    array( 'db' => 'sub_district',     'dt' => 23 ),
    array( 'db' => 'district',     'dt' => 24 ),
    array( 'db' => 'province',     'dt' => 25 ),
    array( 'db' => 'postal_code',     'dt' => 26 ),
    array( 'db' => 'region',     'dt' => 27 ),
    array( 'db' => 'country',     'dt' => 28 ),
    array( 'db' => 'contactname',     'dt' => 29 ),
    array( 'db' => 'contactnumber',     'dt' => 30 ),
    array( 'db' => 'typeofcontract',     'dt' => 31 ),
    array( 'db' => 'startwarranty',     'dt' => 32 ),
    array( 'db' => 'endwarranty',     'dt' => 33 ),
    array( 'db' => 'ups_status',     'dt' => 34 ),
    array( 'db' => 'pmyear',     'dt' => 35 ),
    array( 'db' => 'nextpm',     'dt' => 36 ),
    array( 'db' => 'sla_condition',     'dt' => 37 ),
    array( 'db' => 'sla_response',     'dt' => 38 ),
    array( 'db' => 'sla_recovery',     'dt' => 39 )
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
/*, '
    asset_tracker.fse_code = fse.fse_code
    AND asset_tracker.customer_no = customers.customer_no
    AND asset_tracker.location_code = location.location_code 
    AND sale_order.sale_order_no = asset_tracker.sale_order_no
    AND asset_tracker.itemnumber = material_master_record.itemnumber
    '*/
?>
