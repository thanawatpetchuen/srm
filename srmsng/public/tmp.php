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
            UNION SELECT abbr, cm_time, service_request_id, location_code, customer_name, sitename, 
                title, start_time, ot_evening, 
                IF(CAST(start_time AS TIME) >= CAST("08:30:00" AS TIME) AND CAST(start_time AS TIME) < CAST("17:30:00" AS TIME), "YES", ""), start_travel_time, arrived_time 
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