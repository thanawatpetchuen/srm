<?php
 
// SQL statement for "fetchTicketFSE.php" is 

/* 
SELECT cm_id, sng_code, model, power, battery, quantity, sitename, name, phone_number, email, problem_type, asset_problem, asset_detected, 										
			correction_description, correction_detail, cause_description, cause_detail, solution, suggestions, ups_status, groupFSE,
            job_status, cm_time, request_time, start_time, close_time
FROM (
        SELECT srm_request.cm_id,  srm_request.sng_code, model,  power,  battery, quantity, sitename, name, phone_number, srm_request.email,  problem_type, asset_problem,  asset_detected, 
                correction_detail, correction_description,  solution, suggestions, asset_tracker.ups_status, cause_description, cause_detail, fse.engname,
      			GROUP_CONCAT(DISTINCT fse.fse_code ORDER BY fse.fse_code ASC SEPARATOR ',' ) AS groupFSECode, 
      			GROUP_CONCAT(DISTINCT fse.engname ORDER BY engname ASC SEPARATOR '<br>') AS groupFSE, 
      			job_status, cm_time, request_time, start_time, close_time, fse.fse_code 
      		FROM srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse
        	WHERE asset_tracker.sng_code = srm_request.sng_code 
        		AND location.location_code = asset_tracker.location_code
        		AND asset_tracker.itemnumber = material_master_record.itemnumber
        		AND srm_request.cm_id = job_fse.job_id 
        		AND fse.fse_code = job_fse.fse_code
        		AND srm_request.cause_id = root_cause.cause_id
        		AND srm_request.correction_id = correction.correction_id             
            GROUP BY srm_request.cm_id
     ) AS sub_q 
WHERE FIND_IN_SET('456', sub_q.groupFSECode) 
*/


session_start(); // Start session for using session variable.

$account = $_GET["fse_code"]; 

$statement_after_from = "(SELECT srm_request.cm_id,  srm_request.sng_code, model,  power,  battery, quantity, sitename, name, 
                                phone_number, srm_request.email,  problem_type, asset_problem,  asset_detected, 		  							
                                correction_detail, correction_description,  solution, suggestions, asset_tracker.ups_status, cause_description, 
                                cause_detail, fse.engname,
                                GROUP_CONCAT(DISTINCT fse.fse_code ORDER BY fse.fse_code ASC SEPARATOR ',' ) AS groupFSECode, 
                                GROUP_CONCAT(DISTINCT fse.engname ORDER BY engname ASC SEPARATOR '<br>') AS groupFSE, 
                                job_status, cm_time, request_time, start_time, close_time, fse.fse_code, notes
                        FROM srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse
                        WHERE asset_tracker.sng_code = srm_request.sng_code 
                            AND location.location_code = asset_tracker.location_code
                            AND asset_tracker.itemnumber = material_master_record.itemnumber
                            AND srm_request.cm_id = job_fse.job_id 
                            AND fse.fse_code = job_fse.fse_code
                            AND srm_request.cause_id = root_cause.cause_id
                            AND srm_request.correction_id = correction.correction_id             
                        GROUP BY srm_request.cm_id) AS sub_q 
                    WHERE FIND_IN_SET('$account', sub_q.groupFSECode)";
        
    
// Table's primary key
$primaryKey = 'cm_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier.

// Selected columns
$columns = array(
    array( 'db' => "cm_id", 'dt' => 0),
    array( 'db' => 'sng_code', 'dt' => 1 ),
    array( 'db' => 'model', 'dt' => 2 ),
    array( 'db' => 'power', 'dt' => 3 ),
    array( 'db' => 'battery', 'dt' => 4 ),
    array( 'db' => 'quantity', 'dt' => 5 ),
    array( 'db' => 'sitename', 'dt' => 6 ),
    array( 'db' => 'name',  'dt' => 7 ),
    array( 'db' => 'phone_number',   'dt' => 8 ),
    array( 'db' => 'email',     'dt' => 9 ),
    array( 'db' => 'problem_type',     'dt' => 10 ),
    array( 'db' => 'asset_problem',     'dt' => 11 ),
    array( 'db' => 'asset_detected',     'dt' => 12 ),
    array( 'db' => 'correction_description',     'dt' => 13 ),
    array( 'db' => 'correction_detail',     'dt' => 14 ),
    array( 'db' => 'cause_description',     'dt' => 15 ),
    array( 'db' => 'cause_detail',     'dt' => 16 ),
    array( 'db' => 'solution',     'dt' => 17 ),
    array( 'db' => 'suggestions',     'dt' => 18 ),
    array( 'db' => 'ups_status',     'dt' => 19 ),
    array( 'db' => "groupFSE", 'dt' => 20 ),
    array( 'db' => 'job_status',     'dt' => 21 ),
    array( 'db' => 'cm_time',     'dt' => 22 ),
    array( 'db' => 'request_time',     'dt' => 23 ),
    array( 'db' => 'start_time',     'dt' => 24 ),
    array( 'db' => 'close_time',     'dt' => 25 ),
    array( 'db' => 'notes',     'dt' => 26 ),
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