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

$table = "(SELECT service_request.service_request_id,
            service_request.title,
            service_request.status,
            service_request.contact_name,
            service_request.contact_number,
            service_request.alternate_number,
            IF(service_fse.is_leader>0, CONCAT('(Leader) ', fse.engname), fse.engname) AS fse_engname,
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
            service_request.due_date
            FROM service_request, asset_tracker, fse, location, service_fse, service_asset
            WHERE service_request.service_request_id = service_asset.service_request_id
            AND service_request.service_request_id = service_fse.service_request_id
            AND service_asset.sng_code = asset_tracker.sng_code
            AND service_fse.fse_code = fse.fse_code
            AND asset_tracker.location_code = location.location_code) AS sub_q";

//Filter by URL attributes
if (!empty($_GET['maintenance_plan_id'])) {
    $table = $table . ", maintenance_service
                WHERE sub_q.service_request_id = maintenance_service.service_request_id
                AND maintenance_plan_id = '" . $_GET['maintenance_plan_id'] . "'";
}

//Group table by service_request_id
$table = $table . " GROUP BY sub_q.service_request_id";

// Table's primary key
$primaryKey = 'title';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'sub_q.service_request_id', 'dt' => 0),
    array( 'db' => 'title', 'dt' => 1 ),
    array( 'db' => 'status', 'dt' => 2 ),
    array( 'db' => 'contact_name', 'dt' => 3 ),
    array( 'db' => 'contact_number', 'dt' => 4 ),
    array( 'db' => 'alternate_number', 'dt' => 5 ),
    array( 'db' => "GROUP_CONCAT(DISTINCT fse_engname ORDER BY fse_engname ASC SEPARATOR '<br>')", 'dt' => 6 ),
    array( 'db' => "GROUP_CONCAT(DISTINCT fse_engname ORDER BY fse_engname ASC SEPARATOR '<br>')", 'dt' => 7 ),
    array( 'db' => 'work_class', 'dt' => 8 ),
    array( 'db' => 'sitename', 'dt' => 9 ),
    array( 'db' => 'location_code', 'dt' => 10 ),
    array( 'db' => 'house_no', 'dt' => 11 ),
    array( 'db' => 'village_no', 'dt' => 12 ),
    array( 'db' => 'road', 'dt' => 13 ),
    array( 'db' => 'sub_district', 'dt' => 14 ),
    array( 'db' => 'district', 'dt' => 15 ),
    array( 'db' => 'province', 'dt' => 16 ),
    array( 'db' => 'postal_code', 'dt' => 17 ),
    array( 'db' => 'region', 'dt' => 18 ),
    array( 'db' => 'country', 'dt' => 19 ),
    array( 'db' => 'store_phone', 'dt' => 20 ),
    array( 'db' => 'due_date', 'dt' => 21 ),
);

// SQL server connection information
$sql_details = array(
    'user' => 'admin',
    'pass' => '1234',
    'db'   => 'sngbase',
    'host' => 'localhost'
);

require( 'ssp.class.php' );

echo json_encode(
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
);
?>
