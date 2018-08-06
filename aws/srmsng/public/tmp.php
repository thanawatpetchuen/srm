<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


session_start();

require '../vendor/autoload.php';
require '../src/config/db.php';
// require 'login.html';

$c = new \Slim\Container(); //Create Your container

//Override the default Not Found Handler
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
    };
};

$app = new \Slim\App($c);

$app->get('/api/admin/workload', function (Request $request, Response $response) {
    $fse_code = $request->getParam('fse_code');
    
    $sql = "srm_request, asset_tracker, location, material_master_record, fse, root_cause, 
                correction, job_fse 
            WHERE srm_request.sng_code = asset_tracker.sng_code   
                AND srm_request.job_status = 'Closed' 
                AND location.location_code = asset_tracker.location_code
                AND asset_tracker.itemnumber = material_master_record.itemnumber
                AND srm_request.cm_id = job_fse.job_id 
                AND fse.fse_code = job_fse.fse_code
                AND srm_request.cause_id = root_cause.cause_id
                AND srm_request.correction_id = correction.correction_id GROUP BY srm_request.cm_id  
            UNION SELECT abbr, cm_time, service_request_id, location_code, customer_name, sitename, work_class, 
                IF(CAST(complete_time AS TIME) >= CAST("08:30:00" AS TIME) AND CAST(complete_time AS TIME) < CAST("17:30:00" AS TIME), "YES"), 
                IF(CAST(complete_time AS TIME) >= CAST("17:30:00" AS TIME) AND CAST(complete_time AS TIME) < CAST("24:00:00" AS TIME), "YES"), 
                IF(CAST(complete_time AS TIME) <= CAST("24:00:00" AS TIME) AND CAST(complete_time AS TIME) < CAST("08:30:00" AS TIME), "YES"), 
                TIMEDIFF(CAST(arrived_time AS DATETIME), CAST(start_travel_time AS DATETIME)), 
                TIMEDIFF(CAST(complete_time AS DATETIME), CAST(start_time AS DATETIME)),
                TIMEDIFF(CAST(complete_time AS DATETIME), CAST(start_travel_time AS DATETIME)),
                IF(status != 'Closed', 'Incomplete'),
                IFNULL(holiday, IF(DAYOFWEEK(CAST(start_time AS DATETIME)) = 6 OR  DAYOFWEEK(CAST(start_time AS DATETIME)) = 1, DAYNAME(start_time)), holiday) 
                notes
                SELECT IFNULL(sub_q.holiday, IF((sub_q.dof = 1 OR sub_q.dof=6), DAYNAME(sub_q.start_time), "")) FROM (SELECT start_time, DAYOFWEEK(start_time) AS dof, holiday from srm_request) AS sub_q
                start_travel_time, arrived_time, 
                GROUP_CONCAT(DISTINCT fse_engname ORDER BY fse_engname ASC SEPARATOR '<br>') AS groupFSE,
    TIMEDIFF(CAST(close_time AS DATETIME), CAST(start_time AS DATETIME)), status FROM (SELECT service_request.service_request_id,
    service_request.title,
    service_request.status,
    service_request.contact_name,
    service_request.contact_number,
    service_request.alternate_number,
    service_request.description,
    service_request.close_time,
    service_request.start_time,
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

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $message = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return json_encode($message);

    } catch(PDOException $e){
        $db = null;
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


// Update FSE
$app->put('/api/admin/updatefse', function(Request $request, Response $response){
    $fse_code = $request->getParam('fse_code');
    $thainame = $request->getParam('thainame');
    $engname = $request->getParam('engname');
    $abbr = $request->getParam('abbr');
    $company = $request->getParam('company');
    $position = $request->getParam('position');
    $service_center = $request->getParam('service_center');
    $section = $request->getParam('section');
    $team = $request->getParam('team');
    $status = $request->getParam('status');
    $email = $request->getParam('email');
    $phone = $request->getParam('phone');

    $sql = "UPDATE fse SET
                thainame       = :set_thainame,
                engname        = :set_engname,
                abbr           = :set_abbr,
                company        = :set_company,
                position       = :set_position,
                service_center = :set_service_center,
                section        = :set_section,
                team           = :set_team,
                status         = :set_status,
                email          = :set_email,
                phone          = :set_phone
            WHERE fse_code = '$fse_code'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_thainame', $thainame);
        $stmt->bindParam(':set_engname', $engname);
        $stmt->bindParam(':set_abbr', $abbr);
        $stmt->bindParam(':set_company', $company);
        $stmt->bindParam(':set_position', $position);
        $stmt->bindParam(':set_service_center', $service_center);
        $stmt->bindParam(':set_section', $section);
        $stmt->bindParam(':set_team', $team);
        $stmt->bindParam(':set_status', $status);
        $stmt->bindParam(':set_email', $email);
        $stmt->bindParam(':set_phone', $phone);

        $stmt->execute();

        $db = null;
        system_log('Update fse informmation: '. $engname);
        return 'SUCCESS';
    } catch(PDOException $e){
        $db = null;
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

$app->run();



/*
SELECT sub_q.abbr, sub_q.date_start,  sub_q.service_request_id, sub_q.location_code, sub_q.customer_name, sub_q.sitename, sub_q.work_class,
	    IF(sub_q.complete_time  >= CAST("08:30:00" AS TIME) AND sub_q.complete_time < CAST("17:30:00" AS TIME), "YES", "") AS wh_time,
        IF(sub_q.complete_time  >= CAST("17:30:00" AS TIME) AND sub_q.complete_time < CAST("24:00:00" AS TIME), "YES", "") AS ot_evening, 
        IF(sub_q.complete_time  <= CAST("24:00:00" AS TIME) AND sub_q.complete_time  < CAST("08:30:00" AS TIME), "YES", "") AS ot_modnight,
        TIMEDIFF(sub_q.arrived_time, sub_q.start_travel_time) AS travel_time,
        TIMEDIFF(sub_q.complete_time, sub_q.start_time) AS work_period,
        TIMEDIFF(complete_time, start_travel_time) AS total_period,
        IF(status != 'Closed', 'Incomplete', '') AS work_status,
        sub_q.notes,
        IFNULL(sub_q.holiday,  IF((sub_q.dof = 1 OR sub_q.dof=6), DAYNAME(sub_q.start_time), "")) AS holiday
FROM
    (SELECT fse.abbr, CAST(service_request.start_time AS DATE) AS date_start, service_request.service_request_id, 
    location.location_code, customers.customer_name, 		location.sitename, service_request.work_class, arrived_time, 
    start_travel_time, service_request.complete_time, DAYOFWEEK(service_request.start_time) AS dof, service_request.holiday, service_request.notes, 
    service_request.start_time,service_request.status
          		FROM service_request, service_fse, service_asset, asset_tracker, fse, location, customers
                WHERE service_request.service_request_id = service_asset.service_request_id
    				AND service_request.service_request_id = service_fse.service_request_id
    				AND service_asset.sng_code = asset_tracker.sng_code
    				AND service_fse.fse_code = fse.fse_code
    				AND asset_tracker.location_code = location.location_code
                    AND asset_tracker.customer_no = customers.customer_no) as sub_q
*/         
/*
SELECT sub_q2.abbr, sub_q2.date_start,  sub_q2.cm_id, sub_q2.location_code, sub_q2.customer_name, sub_q2.sitename, sub_q2.work_class,
	    	IF(sub_q2.complete_time  >= CAST("08:30:00" AS TIME) AND sub_q2.complete_time < CAST("17:30:00" AS TIME), "YES", "") AS wh_time,
        	IF(sub_q2.complete_time  >= CAST("17:30:00" AS TIME) AND sub_q2.complete_time < CAST("24:00:00" AS TIME), "YES", "") AS ot_evening, 
        	IF(sub_q2.complete_time  <= CAST("24:00:00" AS TIME) AND sub_q2.complete_time  < CAST("08:30:00" AS TIME), "YES", "") AS ot_modnight,
        	TIMEDIFF(sub_q2.arrived_time, sub_q2.start_travel_time) AS travel_time,
        	TIMEDIFF(sub_q2.complete_time, sub_q2.start_time) AS work_period,
        	TIMEDIFF(complete_time, start_travel_time) AS total_period,
        	IF(sub_q2.job_status != 'Closed', 'Incomplete', '') AS work_status,
        	sub_q2.notes,
        	IFNULL(sub_q2.holiday,  IF((sub_q2.dof = 1 OR sub_q2.dof=6), DAYNAME(sub_q2.start_time), "")) AS holiday 
FROM (SELECT fse.abbr, CAST(srm_request.start_time AS DATE) AS date_start,
               				srm_request.cm_id, location.location_code, customers.customer_name, location.sitename,
               				srm_request.work_class, srm_request.arrived_time, srm_request.start_travel_time, srm_request.complete_time,
               				DAYOFWEEK(srm_request.start_time) AS dof, srm_request.holiday, srm_request.notes, srm_request.start_time,
               				srm_request.job_status
               			FROM srm_request, asset_tracker, location, material_master_record, fse, 
                        	root_cause, correction, job_fse, customers
                        	WHERE asset_tracker.sng_code = srm_request.sng_code 
                            	AND location.location_code = asset_tracker.location_code
                            	AND asset_tracker.itemnumber = material_master_record.itemnumber
                            	AND srm_request.cm_id = job_fse.job_id 
                            	AND fse.fse_code = job_fse.fse_code
                            	AND srm_request.cause_id = root_cause.cause_id
               					AND asset_tracker.customer_no = customers.customer_no 
                            	AND srm_request.correction_id = correction.correction_id
               					AND asset_tracker.customer_no = customers.customer_no)
                        	AS sub_q2
*/

/*
SELECT * FROM (SELECT sub_q2.abbr, sub_q2.date_start,  sub_q2.cm_id, sub_q2.location_code, sub_q2.customer_name, sub_q2.sitename, sub_q2.work_class,
    IF(sub_q2.complete_time  >= CAST('08:30:0' AS TIME) AND sub_q2.complete_time < CAST('17:30:00' AS TIME), 'YES', '') AS wh_time,
    IF(sub_q2.complete_time  >= CAST('17:30:00' AS TIME) AND sub_q2.complete_time < CAST('24:00:00' AS TIME), 'YES', '') AS ot_evening, 
    IF(sub_q2.complete_time  <= CAST('24:00:00' AS TIME) AND sub_q2.complete_time  < CAST('08:30:00' AS TIME), 'YES', '') AS ot_midnight,
    TIMEDIFF(sub_q2.arrived_time, sub_q2.start_travel_time) AS travel_time,
    TIMEDIFF(sub_q2.complete_time, sub_q2.start_time) AS work_period,
    TIMEDIFF(complete_time, start_travel_time) AS total_period,
    IF(sub_q2.job_status != 'Closed', 'Incomplete', '') AS work_status,
    sub_q2.notes,
    IFNULL(sub_q2.holiday,  IF((sub_q2.dof = 1 OR sub_q2.dof=6), DAYNAME(sub_q2.start_time), '')) AS holiday 
FROM (SELECT fse.abbr, CAST(srm_request.start_time AS DATE) AS date_start,
                       srm_request.cm_id, location.location_code, customers.customer_name, location.sitename,
                       srm_request.work_class, srm_request.arrived_time, srm_request.start_travel_time, srm_request.complete_time,
                       DAYOFWEEK(srm_request.start_time) AS dof, srm_request.holiday, srm_request.notes, srm_request.start_time,
                       srm_request.job_status
                   FROM srm_request, asset_tracker, location, material_master_record, fse, 
                    root_cause, correction, job_fse, customers
                    WHERE asset_tracker.sng_code = srm_request.sng_code 
                        AND location.location_code = asset_tracker.location_code
                        AND asset_tracker.itemnumber = material_master_record.itemnumber
                        AND srm_request.cm_id = job_fse.job_id 
                        AND job_fse.fse_code = '$fse_code'
                        AND fse.fse_code = '$fse_code'  
                        AND srm_request.cause_id = root_cause.cause_id
                           AND asset_tracker.customer_no = customers.customer_no 
                        AND srm_request.correction_id = correction.correction_id
                           AND asset_tracker.customer_no = customers.customer_no)
                    AS sub_q2
UNION SELECT sub_q.abbr, sub_q.date_start,  sub_q.service_request_id, sub_q.location_code, sub_q.customer_name, sub_q.sitename, sub_q.work_class,
IF(sub_q.complete_time  >= CAST('08:30:00' AS TIME) AND sub_q.complete_time < CAST('17:30:00' AS TIME), 'YES', '') AS wh_time,
IF(sub_q.complete_time  >= CAST('17:30:00' AS TIME) AND sub_q.complete_time < CAST('24:00:00' AS TIME), 'YES', '') AS ot_evening, 
IF(sub_q.complete_time  <= CAST('24:00:00' AS TIME) AND sub_q.complete_time  < CAST('08:30:00' AS TIME), 'YES', '') AS ot_modnight,
TIMEDIFF(sub_q.arrived_time, sub_q.start_travel_time) AS travel_time,
TIMEDIFF(sub_q.complete_time, sub_q.start_time) AS work_period,
TIMEDIFF(complete_time, start_travel_time) AS total_period,
IF(status != 'Closed', 'Incomplete', '') AS work_status,
sub_q.notes,
IFNULL(sub_q.holiday,  IF((sub_q.dof = 1 OR sub_q.dof=6), DAYNAME(sub_q.start_time), '')) AS holiday
FROM
(SELECT fse.abbr, CAST(service_request.start_time AS DATE) AS date_start, service_request.service_request_id, 
location.location_code, customers.customer_name, 		location.sitename, service_request.work_class, arrived_time, 
start_travel_time, service_request.complete_time, DAYOFWEEK(service_request.start_time) AS dof, service_request.holiday, service_request.notes, 
service_request.start_time,service_request.status
          FROM service_request, service_fse, service_asset, asset_tracker, fse, location, customers
        WHERE service_request.service_request_id = service_asset.service_request_id
            AND service_request.service_request_id = service_fse.service_request_id
            AND service_asset.sng_code = asset_tracker.sng_code
            AND service_fse.fse_code = '$fse_code'
            AND fse.fse_code = '$fse_code'
            AND asset_tracker.location_code = location.location_code
            AND asset_tracker.customer_no = customers.customer_no) as sub_q) AS sub_q3 
            WHERE sub_q3.date_start >= CAST('$start_time' AS DATE)  
                      AND sub_q3.date_start <= CAST('$stop_time' AS DATE)
*/

/*
SELECT * FROM (SELECT sub_q2.abbr, sub_q2.date_start,  sub_q2.cm_id, sub_q2.location_code, sub_q2.customer_name, sub_q2.sitename, sub_q2.work_class,
    IF(sub_q2.complete_time  >= CAST('08:30:0' AS TIME) AND sub_q2.complete_time < CAST('17:30:00' AS TIME), 'YES', '') AS wh_time,
    IF(sub_q2.complete_time  >= CAST('17:30:00' AS TIME) AND sub_q2.complete_time < CAST('24:00:00' AS TIME), 'YES', '') AS ot_evening, 
    IF(sub_q2.complete_time  <= CAST('24:00:00' AS TIME) AND sub_q2.complete_time  < CAST('08:30:00' AS TIME), 'YES', '') AS ot_midnight,
    TIMEDIFF(sub_q2.arrived_time, sub_q2.start_travel_time) AS travel_time,
    TIMEDIFF(sub_q2.complete_time, sub_q2.start_time) AS work_period,
    TIMEDIFF(complete_time, start_travel_time) AS total_period,
    IF(sub_q2.job_status != 'Closed', 'Incomplete', '') AS work_status,
    sub_q2.notes,
    IFNULL(sub_q2.holiday,  IF((sub_q2.dof = 1 OR sub_q2.dof=6), DAYNAME(sub_q2.start_time), '')) AS holiday 
FROM (SELECT fse.abbr, CAST(srm_request.start_time AS DATE) AS date_start,
                       srm_request.cm_id, location.location_code, customers.customer_name, location.sitename,
                       srm_request.work_class, srm_request.arrived_time, srm_request.start_travel_time, srm_request.complete_time,
                       DAYOFWEEK(srm_request.start_time) AS dof, srm_request.holiday, srm_request.notes, srm_request.start_time,
                       srm_request.job_status
                   FROM srm_request, asset_tracker, location, material_master_record, fse, 
                    root_cause, correction, job_fse, customers
                    WHERE asset_tracker.sng_code = srm_request.sng_code 
                        AND location.location_code = asset_tracker.location_code
                        AND asset_tracker.itemnumber = material_master_record.itemnumber
                        AND srm_request.cm_id = job_fse.job_id 
                        AND job_fse.fse_code = '$fse_code'
                        AND fse.fse_code = '$fse_code'  
                        AND srm_request.cause_id = root_cause.cause_id
                           AND asset_tracker.customer_no = customers.customer_no 
                        AND srm_request.correction_id = correction.correction_id
                           AND asset_tracker.customer_no = customers.customer_no)
                    AS sub_q2
UNION SELECT sub_q.abbr, sub_q.date_start,  sub_q.service_request_id, sub_q.location_code, sub_q.customer_name, sub_q.sitename, sub_q.work_class,
IF(sub_q.complete_time  >= CAST('08:30:00' AS TIME) AND sub_q.complete_time < CAST('17:30:00' AS TIME), 'YES', '') AS wh_time,
IF(sub_q.complete_time  >= CAST('17:30:00' AS TIME) AND sub_q.complete_time < CAST('24:00:00' AS TIME), 'YES', '') AS ot_evening, 
IF(sub_q.complete_time  <= CAST('24:00:00' AS TIME) AND sub_q.complete_time  < CAST('08:30:00' AS TIME), 'YES', '') AS ot_modnight,
TIMEDIFF(sub_q.arrived_time, sub_q.start_travel_time) AS travel_time,
TIMEDIFF(sub_q.complete_time, sub_q.start_time) AS work_period,
TIMEDIFF(complete_time, start_travel_time) AS total_period,
IF(status != 'Closed', 'Incomplete', '') AS work_status,
sub_q.notes,
IFNULL(sub_q.holiday,  IF((sub_q.dof = 1 OR sub_q.dof=6), DAYNAME(sub_q.start_time), '')) AS holiday
FROM
(SELECT fse.abbr, CAST(service_request.start_time AS DATE) AS date_start, service_request.service_request_id, 
location.location_code, customers.customer_name, 		location.sitename, service_request.work_class, arrived_time, 
start_travel_time, service_request.complete_time, DAYOFWEEK(service_request.start_time) AS dof, service_request.holiday, service_request.notes, 
service_request.start_time,service_request.status
          FROM service_request, service_fse, service_asset, asset_tracker, fse, location, customers
        WHERE service_request.service_request_id = service_asset.service_request_id
            AND service_request.service_request_id = service_fse.service_request_id
            AND service_asset.sng_code = asset_tracker.sng_code
            AND service_fse.fse_code = '$fse_code'
            AND fse.fse_code = '$fse_code'
            AND asset_tracker.location_code = location.location_code
            AND asset_tracker.customer_no = customers.customer_no) as sub_q) AS sub_q3 
            WHERE YEAR(sub_q3.date_start) = '$year'  
                      AND MONTH(sub_q3.date_start) = '$month'
*/