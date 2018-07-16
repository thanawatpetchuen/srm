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
// $table = 'srm_request, asset_tracker, location, material_master_record, fse';

$table = "fse WHERE fse_code != 0"; 
// Table's primary key
$primaryKey = 'fse_code';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
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