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
session_start();
$account = $_SESSION["account_no"];
$table = "srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse 
        WHERE fse.fse_code = '$account' 
        AND asset_tracker.sng_code = srm_request.sng_code 
        AND location.location_code = asset_tracker.location_code
        AND asset_tracker.itemnumber = material_master_record.itemnumber
        AND srm_request.cm_id = job_fse.job_id
        AND job_fse.fse_code = '$account' 
        AND srm_request.cause_id = root_cause.cause_id
        AND srm_request.correction_id = correction.correction_id";
        
    
// Table's primary key
$primaryKey = 'cm_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => "srm_request.cm_id", 'dt' => 0),
    array( 'db' => 'srm_request.sng_code', 'dt' => 1 ),
    array( 'db' => 'model', 'dt' => 2 ),
    array( 'db' => 'power', 'dt' => 3 ),
    array( 'db' => 'battery', 'dt' => 4 ),
    array( 'db' => 'quantity', 'dt' => 5 ),
    array( 'db' => 'sitename', 'dt' => 6 ),
    array( 'db' => 'name',  'dt' => 7 ),
    array( 'db' => 'phone_number',   'dt' => 8 ),
    array( 'db' => 'srm_request.email',     'dt' => 9 ),
    array( 'db' => 'problem_type',     'dt' => 10 ),
    array( 'db' => 'asset_problem',     'dt' => 11 ),
    array( 'db' => 'asset_detected',     'dt' => 12 ),
    array( 'db' => 'correction_description',     'dt' => 13 ),
    array( 'db' => 'correction_detail',     'dt' => 14 ),
    array( 'db' => 'cause_description',     'dt' => 15 ),
    array( 'db' => 'cause_detail',     'dt' => 16 ),
    array( 'db' => 'solution',     'dt' => 17 ),
    array( 'db' => 'suggestions',     'dt' => 18 ),
    array( 'db' => 'asset_tracker.ups_status',     'dt' => 19 ),
    array( 'db' => 'job_status',     'dt' => 20 ),
    array( 'db' => 'cm_time',     'dt' => 21 ),
    array( 'db' => 'request_time',     'dt' => 22 ),
    array( 'db' => 'close_time',     'dt' => 23 ),
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