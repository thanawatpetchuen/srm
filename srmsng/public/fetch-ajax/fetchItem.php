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

// SELECT cm_id, srm_request.sng_code, model, sitename, name, phone_number, email, asset_problem, fse,
// job_status, fixed_by, asset_detected, solution, ups_status, cause, cm_time,
// record_time, close_time, request_time, date_cm
 
//DB table to use

$table = 'material_master_record'; 


// Table's primary key
$primaryKey = 'itemnumber';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => "itemnumber", 'dt' => 0),
    array( 'db' => 'model', 'dt' => 1 ),
    array( 'db' => 'power',     'dt' => 2 ),
    array( 'db' => 'item_class', 'dt' => 3 ),
    array( 'db' => 'category', 'dt' => 4 ),
    array( 'db' => 'is_lot', 'dt' => 5 ),
    array( 'db' => 'is_serial', 'dt' => 6 ),
    array( 'db' => 'is_warranty',   'dt' => 7 ),
    array( 'db' => 'created_on', 'dt' => 8 ),
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
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
);
?>