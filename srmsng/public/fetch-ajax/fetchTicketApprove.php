<?php
 
// SQL statement for "fetchTicket.php" is 

//  SELECT srm_request.cm_id, srm_request.sng_code, model, power, battery, quantity, sitename, name, phone_number, srm_request.email, problem_type, asset_problem, asset_detected, correction_description, correction_detail, cause_description, cause_detail, solution, suggestions, asset_tracker.ups_status, GROUP_CONCAT(DISTINCT fse.engname ORDER BY engname ASC SEPARATOR '<br>') AS groupFSE, job_type, job_status, cm_time, request_time, start_time, close_time
//  FROM srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse 
//  WHERE asset_tracker.sng_code = srm_request.sng_code 
//      AND location.location_code = asset_tracker.location_code
//      AND asset_tracker.itemnumber = material_master_record.itemnumber
//      AND srm_request.cm_id = job_fse.job_id 
//      AND fse.fse_code = job_fse.fse_code
//      AND srm_request.cause_id = root_cause.cause_id
//      AND srm_request.correction_id = correction.correction_id 
//  GROUP BY srm_request.cm_id


 
$statement_after_for = 'srm_request, asset_tracker, location, material_master_record, fse, 
                        root_cause, correction, job_fse 
                        WHERE asset_tracker.sng_code = srm_request.sng_code 
                            AND location.location_code = asset_tracker.location_code
                            AND asset_tracker.itemnumber = material_master_record.itemnumber
                            AND srm_request.cm_id = job_fse.job_id 
                            AND fse.fse_code = job_fse.fse_code
                            AND srm_request.cause_id = root_cause.cause_id
                            AND srm_request.correction_id = correction.correction_id 
                            AND srm_request.job_status = "Pending Approve"
                        GROUP BY srm_request.cm_id';

// Table's primary key
$primaryKey = 'cm_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

// These array elements are selected columns that appear after SELECT in the SQL statement.
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
    array( 'db' => "GROUP_CONCAT(DISTINCT fse.engname ORDER BY engname ASC SEPARATOR '<br>') AS groupFSE", 'dt' => 20 ),
    array( 'db' => 'job_type',     'dt' => 21 ),
    array( 'db' => 'job_status',     'dt' => 22 ),
    array( 'db' => 'cm_time',     'dt' => 23 ),
    array( 'db' => 'request_time',     'dt' => 24 ),
    array( 'db' => 'start_time',     'dt' => 25 ),
    array( 'db' => 'close_time',     'dt' => 26 ),
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
    SSP::complex( $_GET, $sql_details, $statement_after_for, $primaryKey, $columns)
);
?>