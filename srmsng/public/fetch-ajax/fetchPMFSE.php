<?php

// SQL statement for "fetchTicketFSE.php" is 

/*
SELECT * 
FROM (
    SELECT sub_q.service_request_id, title, status, contact_name, contact_number, alternate_number,
                GROUP_CONCAT(DISTINCT fse_engname ORDER BY fse_engname ASC SEPARATOR '<br>') AS groupFSE,
    			GROUP_CONCAT(DISTINCT fse_code ORDER BY fse_code ASC SEPARATOR ',') AS groupFSECODE,
                work_class, sitename, location_code, house_no, village_no, road, sub_district, district, province,
                postal_code, region, country, store_phone, due_date, sng_code
    FROM (
            SELECT service_request.service_request_id, service_request.title, service_request.status,
                    service_request.contact_name, service_request.contact_number, service_request.alternate_number,
                    IF(service_fse.is_leader>0, CONCAT('(Leader) ', fse.engname), fse.engname) AS fse_engname,
                    service_request.work_class, location.sitename, location.location_code, location.house_no,
                    location.village_no, location.soi, location.road, location.sub_district, location.district,
                    location.province, location.postal_code, location.region, location.country,
                    location.store_phone, service_request.due_date, service_asset.sng_code, fse.fse_code
            FROM service_request, asset_tracker, fse, location, service_fse, service_asset
            WHERE service_request.service_request_id = service_asset.service_request_id
                    AND service_request.service_request_id = service_fse.service_request_id
                    AND service_asset.sng_code = asset_tracker.sng_code
                    AND service_fse.fse_code = fse.fse_code
                    AND asset_tracker.location_code = location.location_code
         ) AS sub_q GROUP BY sub_q.service_request_id
        ) AS sub_q2 
WHERE FIND_IN_SET('170', sub_q2.groupFSECode)
*/

session_start(); // Start session for using session variable.

$account = $_GET["fse_code"];

$table = "(
    SELECT sub_q.service_request_id, title, status, contact_name, contact_number, alternate_number,
                GROUP_CONCAT(DISTINCT fse_engname ORDER BY fse_engname ASC SEPARATOR '<br>') AS groupFSE,
    			GROUP_CONCAT(DISTINCT fse_code ORDER BY fse_code ASC SEPARATOR ',') AS groupFSECODE,
                work_class, sitename, location_code, house_no, village_no, road, sub_district, district, province,
                postal_code, region, country, store_phone, due_date, sng_code
    FROM (
            SELECT service_request.service_request_id, service_request.title, service_request.status,
                    service_request.contact_name, service_request.contact_number, service_request.alternate_number,
                    IF(service_fse.is_leader>0, CONCAT('(Leader) ', fse.engname), fse.engname) AS fse_engname,
                    service_request.work_class, location.sitename, location.location_code, location.house_no,
                    location.village_no, location.soi, location.road, location.sub_district, location.district,
                    location.province, location.postal_code, location.region, location.country,
                    location.store_phone, service_request.due_date, service_asset.sng_code, fse.fse_code
            FROM service_request, asset_tracker, fse, location, service_fse, service_asset
            WHERE service_request.service_request_id = service_asset.service_request_id
                    AND service_request.service_request_id = service_fse.service_request_id
                    AND service_asset.sng_code = asset_tracker.sng_code
                    AND service_fse.fse_code = fse.fse_code
                    AND asset_tracker.location_code = location.location_code
         ) AS sub_q GROUP BY sub_q.service_request_id
        ) AS sub_q2 
        WHERE FIND_IN_SET('$account', sub_q2.groupFSECode)";


// Table's primary key
$primaryKey = 'service_request_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'service_request_id', 'dt' => 0),
    array( 'db' => 'title', 'dt' => 1 ),
    array( 'db' => 'status', 'dt' => 2 ),
    array( 'db' => 'contact_name', 'dt' => 3 ),
    array( 'db' => 'contact_number', 'dt' => 4 ),
    array( 'db' => 'alternate_number', 'dt' => 5 ),
    array( 'db' => "groupFSE", 'dt' => 6 ),
    array( 'db' => 'work_class', 'dt' => 7 ),
    array( 'db' => 'sitename', 'dt' => 8 ),
    array( 'db' => 'location_code', 'dt' => 9 ),
    array( 'db' => 'house_no', 'dt' => 10 ),
    array( 'db' => 'village_no', 'dt' => 11 ),
    array( 'db' => 'road', 'dt' => 12 ),
    array( 'db' => 'sub_district', 'dt' => 13 ),
    array( 'db' => 'district', 'dt' => 14 ),
    array( 'db' => 'province', 'dt' => 15 ),
    array( 'db' => 'postal_code', 'dt' => 16 ),
    array( 'db' => 'region', 'dt' => 17 ),
    array( 'db' => 'country', 'dt' => 18 ),
    array( 'db' => 'store_phone', 'dt' => 19 ),
    array( 'db' => 'due_date', 'dt' => 20 ),
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
