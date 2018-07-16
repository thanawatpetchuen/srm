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
$sng_code = $_GET['sng_code'];
// return $sng_code;
// $sng_code = '6227';

$table = "srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse 
WHERE asset_tracker.sng_code = ' . $sng_code .' 
AND srm_request.job_status != 'Closed' 
AND location.location_code = asset_tracker.location_code
AND asset_tracker.itemnumber = material_master_record.itemnumber
AND srm_request.cm_id = job_fse.job_id 
AND fse.fse_code = job_fse.fse_code
AND srm_request.cause_id = root_cause.cause_id
AND srm_request.correction_id = correction.correction_id GROUP BY srm_request.cm_id  UNION SELECT service_request_id, due_date, title, description, work_class, 
GROUP_CONCAT(DISTINCT fse_engname ORDER BY fse_engname ASC SEPARATOR '<br>')          AS groupFSE,
status FROM (SELECT service_request.service_request_id,
service_request.title,
service_request.status,
service_request.contact_name,
service_request.contact_number,
service_request.alternate_number,
service_request.description,
IF(service_fse.is_leader>0, CONCAT(fse.engname,' (Leader)'), fse.engname) AS fse_engname,
service_request.work_class,
location.sitename,
location.location_code,
location.house_no,
location.village_no,
location.soi,
location.road,
location.sub_district,
location.district,
location.province,
location.postal_code,
location.region,
location.country,
location.store_phone,
service_request.due_date,
service_asset.sng_code 
FROM service_request, asset_tracker, fse, location, service_fse, service_asset
WHERE service_request.service_request_id = service_asset.service_request_id
AND service_request.service_request_id = service_fse.service_request_id
AND service_asset.sng_code = asset_tracker.sng_code
AND service_fse.fse_code = fse.fse_code
AND asset_tracker.location_code = location.location_code) AS sub_q WHERE sng_code = '$sng_code' GROUP BY sub_q.service_request_id";
// Table's primary key
$primaryKey = 'cm_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => "cm_id", 'dt' => 0),
    array( 'db' => 'cm_time', 'dt'  => 1 ),
    array( 'db' => 'correction_description',     'dt' => 2 ),
    array( 'db' => "solution", 'dt' => 3),
    array( 'db' => 'work_class',     'dt' => 4 ),
    array( 'db' => "GROUP_CONCAT(fse.engname ORDER BY engname ASC SEPARATOR '<br>') AS groupFSE",     'dt' => 5 ),
    array( 'db' => 'job_status',     'dt' => 6 ),
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