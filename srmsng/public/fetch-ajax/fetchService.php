<?php

$statement_after_from = "(SELECT service_request.service_request_id,
            service_request.title, service_request.status, service_request.contact_name,
            service_request.contact_number, service_request.alternate_number, service_request.work_class,
            IF(service_fse.is_leader>0, CONCAT('(Leader) ', fse.engname), fse.engname) AS fse_engname,
            IFNULL(location.sitename,site_location.sitename) AS sitename,
            IFNULL(location.location_code,site_location.location_code) AS location_code,
            IFNULL(location.house_no,site_location.house_no) AS house_no,
            IFNULL(location.village_no,site_location.village_no) AS village_no,
            IFNULL(location.soi,site_location.soi) AS soi,
            IFNULL(location.road,site_location.road) AS road,
            IFNULL(location.sub_district,site_location.sub_district) AS sub_district,
            IFNULL(location.district,site_location.district) AS district,
            IFNULL(location.province,site_location.province) AS province,
            IFNULL(location.postal_code,site_location.postal_code) AS postal_code,
            IFNULL(location.region,site_location.region) AS region,
            IFNULL(location.country,site_location.country) AS country,
            IFNULL(location.store_phone,site_location.store_phone) AS store_phone,
            IF(asset_tracker.sng_code IS NULL,0,1) AS with_asset,
            service_request.due_date
            FROM service_request
                LEFT JOIN service_asset
                    ON service_request.service_request_id = service_asset.service_request_id
                LEFT JOIN service_fse
                    ON service_request.service_request_id = service_fse.service_request_id
                LEFT JOIN asset_tracker
                    ON service_asset.sng_code = asset_tracker.sng_code
                LEFT JOIN fse
                    ON service_fse.fse_code = fse.fse_code
                LEFT JOIN location
                    ON asset_tracker.location_code = location.location_code
			    LEFT JOIN service_request_no_asset
                    ON service_request.service_request_id = service_request_no_asset.service_request_id
                LEFT JOIN location AS site_location
                    ON service_request_no_asset.location_code = site_location.location_code
			";

//Filter by URL attributes
if (!empty($_GET['maintenance_plan_id'])) {
    $statement_after_from = "$statement_after_from , maintenance_service
                WHERE service_request.service_request_id = maintenance_service.service_request_id
                AND maintenance_plan_id = '" . $_GET['maintenance_plan_id'] . "'";
}

//Group table by service_request_id
$statement_after_from = $statement_after_from . ") AS sub_q GROUP BY sub_q.service_request_id";

// Table's primary key
$primaryKey = 'title';

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
    array( 'db' => "fse_engname", 'dt' => 6 ),
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
    array( 'db' => 'with_asset', 'dt' => 22 ),
);

// SQL server connection information
$sql_details = array(
    'user' => 'admin',
    'pass' => '1234',
    'db'   => 'sngbase',
    'host' => 'localhost'
);

require( 'ssp3.class.php' );

echo json_encode(
    SSP::mod( $_GET, $sql_details, $statement_after_from, $primaryKey, $columns)
);
?>
