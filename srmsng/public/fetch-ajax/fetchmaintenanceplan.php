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

$table = "maintenance_plan, maintenance_service, service_request, service_asset, asset_tracker, location
          WHERE maintenance_plan.maintenance_plan_id = maintenance_service.maintenance_plan_id
          AND maintenance_service.service_request_id = service_request.service_request_id
          AND service_request.service_request_id = service_asset.service_request_id
          AND service_asset.sng_code = asset_tracker.sng_code
          AND asset_tracker.location_code = location.location_code GROUP BY maintenance_plan.maintenance_plan_id";

// Table's primary key
$primaryKey = 'title';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'maintenance_plan.maintenance_plan_id', 'dt' => 0),
    array( 'db' => 'maintenance_plan.title', 'dt' => 1 ),
    array( 'db' => 'maintenance_plan.start_date', 'dt' => 2 ),
    array( 'db' => 'maintenance_plan.year_count', 'dt' => 3 ),
    array( 'db' => 'maintenance_plan.times_per_year', 'dt' => 4 ),
    array( 'db' => 'sitename', 'dt' => 5 ),
    array( 'db' => 'asset_tracker.location_code', 'dt' => 6 ),
    array( 'db' => 'house_no', 'dt' => 7 ),
    array( 'db' => 'village_no', 'dt' => 8 ),
    array( 'db' => 'road', 'dt' => 9 ),
    array( 'db' => 'sub_district', 'dt' => 10 ),
    array( 'db' => 'district', 'dt' => 11 ),
    array( 'db' => 'province', 'dt' => 12 ),
    array( 'db' => 'postal_code', 'dt' => 13 ),
    array( 'db' => 'region', 'dt' => 14 ),
    array( 'db' => 'country', 'dt' => 15 ),
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
