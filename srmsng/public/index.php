<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;


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



// Redirect to login page
$app->get('/', function(Request $request, Response $response){
    return $response->withRedirect("/srmsng/public/login");
});

$app->get('/login.html', function(Request $request, Response $response){
    return $response->withRedirect("/srmsng/public/login");
});

// Login
$app->post('/login', function (Request $request, Response $response) {

        $username = hash('sha256', $_POST["username"]);
        $password = hash('sha256', $_POST["password"]);
        $remember = $request->getParam('remember');

        $sql = "SELECT username, account_no, account_status, attempt, is_lock, account_type FROM account WHERE username = '$username' and password = '$password'";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password',  $password);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if ($result){
                $content = json_encode($result);
                $jsonArray = json_decode($content, true);
                $_SESSION['is_lock'] = $jsonArray['is_lock'];
                if (!$jsonArray['is_lock']){
                    $_SESSION['account_status'] = $jsonArray['account_status'];
                    if ($jsonArray['account_status'] != 'LOGIN'){
                        $session = session_id();
                        $_SESSION['account_status'] = "LOGIN";
                        $_SESSION['account_type'] = $jsonArray['account_type'];
                        $_SESSION['account_no'] = $jsonArray['account_no'];
                        $_SESSION['username'] = $jsonArray['username'];
                        $_SESSION['username_unhash'] = $_POST["username"];
                        if($remember == "on"){
                            $_SESSION['remember'] = "on";
                        }else{
                            $_SESSION['remember'] = "off";
                        }

                        // echo $remember;
                        $attempt = 10;
                        $sql = "UPDATE account SET  attempt = :set_attempt, account_status = :set_status, session_id = :set_session WHERE username = '$username'";
                        $stmt = $db->prepare($sql);
                        $session_status = 'LOGIN';
                        $stmt->bindParam(':set_attempt', $attempt);
                        $stmt->bindParam(':set_status', $session_status);
                        $stmt->bindParam(':set_session', $session);
                        $stmt->execute();
                        if ($jsonArray['account_type'] == 'USER'){
                            $success_json = array('statuscode' => '111', 'description' => '/srmsng/public/customer_page');
                            system_log('Login');
                            $db = null;
                            return $response->withJson($success_json);
                        }else{
                            $success_json =array('statuscode' => '112', 'description' => '/srmsng/public/admin_page');
                            system_log('Login');
                            $db = null;
                            return $response->withJson($success_json);
                        }
                    }else{
                        $token = md5(uniqid(rand(), true));
                        $err_json = array('statuscode' => '010', 'description' => 'Another session using the same username is currently signed in. If you choose to proceed, the system will automatically terminate the other session associated to this username.', 'token'=> $token);
                        $_SESSION['token'] = $token;
                        $_SESSION['username_unhash'] = $_POST['username'];
                        $_SESSION['account_type'] = $jsonArray['account_type'];
                        $_SESSION['account_no'] = $jsonArray['account_no'];
                        $_SESSION['username'] = $jsonArray['username'];
                        $_SESSION['account_status'] = "LOGIN";
                        if($remember == "on"){
                            $_SESSION['remember'] = "on";
                        }else{
                            $_SESSION['remember'] = "off";
                        }
                        session_commit();
                        $db = null;
                        return $response->withJson($err_json);
                    }
                }else{
                    $err_json = array('statuscode' => '011', 'description' => 'Account has been locked0');
                    $db = null;
                    return $response->withJson($err_json);
                }
            }else{
                $sql = "SELECT attempt FROM account WHERE username = '$username'";
                $stmt = $db->query($sql);
                $stmt->bindParam(':username', $username);
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                if($result){
                    $content = json_encode($result);
                    $jsonArray = json_decode($content, true);
                    $attempt = $jsonArray['attempt'] - 1;
                    $sql = "UPDATE account SET  attempt = '$attempt'  WHERE username = '$username'";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    if($attempt >= 0){
                        $err_json = array('statuscode' => '101', 'attempt' => $attempt, 'description' => 'Invalid password');
                    }else{
                        $err_json = array('statuscode' => '101', 'attempt' => 0, 'description' => 'Your account has been locked');
                    }
                    if ($attempt == 0){
                        $sql = "UPDATE account SET is_lock = 1 WHERE username = '$username'";
                        $stmt = $db->prepare($sql);
                        $stmt->execute();
                    }
                }else{
                    $err_json = array('statuscode' => '000', 'description' => 'Invalid username and password');
                }
                $db = null;
                return $response->withJson($err_json);
            }
        }catch(PDOException $e){
            $db = null;
            $err_json = array('statuscode' => '001', 'description' => $e->getMessage());
            return $response->withJson($err_json);
        }
});



// Logout
$app->post('/logout', function (Request $request, Response $response) {
    if (isset($_SESSION['account_type'])){
        $date = date('Y-m-d H:i:s', time());
        $username_unhash = $_SESSION['username_unhash'];
        $sql = "UPDATE account SET account_status = :set_status, last_login = :set_date, session_id = :set_session WHERE username_tag = '$username_unhash'";
        $session_status = 'LOGOUT';
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->prepare($sql);
            $session = '';
            $stmt->bindParam(':set_status', $session_status);
            $stmt->bindParam(':set_date', $date);
            $stmt->bindParam(':set_session', $session);

            $stmt->execute();

            system_log('Logout');
            session_destroy();
            session_unset();
            setcookie("user", "", time()-8000000, '/');
            $db = null;

            return $response->withStatus(200)->getBody()->write("/srmsng/public/login");
            

        }catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write("/srmsng/public/login");
        }
    }else{
        return $response->withStatus(400)->getBody()->write("/srmsng/public/login");
    }
});


// Force Logout
$app->post('/logout/forcelogout', function (Request $request, Response $response) {
    $token = $request->getParam('token');
    $username = $_SESSION['username_unhash'];
    $current_session = session_id();
    $tmp = $_SESSION;

    if (isset($_SESSION['token'])){
            if($token == $_SESSION['token']){
                $date = date('Y-m-d H:i:s', time());
                $account_no = $_SESSION['account_no'];
                $sql = "SELECT session_id FROM account WHERE username_tag = '$username' LIMIT 1";
                $session_status = 'LOGIN';

                try{
                    // Get DB Object
                    $db = new db();
                    // Connect
                    $db = $db->connect();

                    $stmt = $db->query($sql);
                    $result = $stmt->fetch(PDO::FETCH_OBJ);

                    // Hijack then destroy session specified.

                    session_id($result->session_id);
                    session_unset($_SESSION);
                    session_commit();
                    session_id($current_session);
                    $_SESSION = $tmp;
                    session_commit();

                    $sql = "UPDATE account SET session_id = :set_session, last_login = :set_date, account_status = :set_status WHERE username_tag = '$username'";
                    try{

                        $stmt = $db->prepare($sql);

                        $stmt->bindParam(':set_session', $current_session);
                        $stmt->bindParam(':set_date', $date);
                        $stmt->bindParam(':set_status', $session_status);

                        $stmt->execute();
                        if ($_SESSION['account_type'] == 'USER'){
                            $success_json = array('statuscode' => '111', 'description' => '/srmsng/public/customer_page');
                            system_log('Force Logout');
                            $db = null;
                            return json_encode($success_json);
                        }else{
                            $success_json =array('statuscode' => '112', 'description' => '/srmsng/public/admin_page');
                            system_log('Force Logout');
                            $db = null;
                            return json_encode($success_json);
                        }

                    }catch(PDOException $e){
                        $db = null;
                        return $response->withStatus(200)->getBody()->write("/srmsng/public/login");
                    }

                }catch(PDOException $e){
                    $db = null;
                    return $response->withStatus(200)->getBody()->write("/srmsng/public/login");
                }
            }else{
                return $response->withStatus(200)->getBody()->write("/srmsng/public/login");
            }
    }else{
        return $response->withStatus(200)->getBody()->write("/srmsng/public/login");
    }
});


// Recover password in login section
$app->post('/login/recover', function (Request $request, Response $response) {
    if(isset($_POST["email"])){
        $email = $_POST["email"];
        $message = $_POST["message"];
        $username = $_POST["username"];
        $status = "UNREAD";
        $date = date('Y-m-d H:i:s', time());

        $sql = "INSERT INTO recover_msg (email, message, status, username, date_time) VALUES
        (:email, :message, :status, :username, :date_time)";

        try{

            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->prepare($sql);

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message',  $message);
            $stmt->bindParam(':status',  $status);
            $stmt->bindParam(':username',  $username);
            $stmt->bindParam(':date_time',  $date);
            $stmt->execute();
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    }else{
        return '/srmsng/public/recover.html';
    }
});

// Get all customers
$app->get('/api/customers', function(Request $request, Response $response){
    $sql = "SELECT * FROM asset_tracker";
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $db = null;
                return $response->withJson($result);

            } catch(PDOException $e) {
                $db = null;
                return $response->withStatus(400)->getBody()->write($e->getmessage());
            }
});

// Get single customer information for editing customer modal.
$app->get('/api/admin/getsinglecustomer', function(Request $request, Response $response){
    $customer_no = $request->getParam('customer_no');

    $sql = "SELECT * FROM customers WHERE customer_no = '$customer_no'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});


// Get single location information for editing location modal.
$app->get('/api/admin/getsinglelocation', function(Request $request, Response $response){
    $location_code = $request->getParam('location_code');

    $sql = "SELECT * FROM location WHERE location_code = '$location_code'";
    try {
        
        // Get DB ObjectS
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get single item information for editing item modal.
$app->get('/api/admin/getsingleitem', function(Request $request, Response $response){
    $itemnumber = $request->getParam('itemnumber');

    $sql = "SELECT * FROM material_master_record WHERE itemnumber = '$itemnumber'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all log
$app->get('/api/admin/log', function(Request $request, Response $response){
    $sql = "SELECT * FROM system_log";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all asset of specifield customer number.
$app->get('/api/customer/asset', function(Request $request, Response $response){
    $account_no = $request->getParam('account_no');

    $sql = "SELECT * FROM asset_tracker WHERE customer_no = '$account_no'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get item for searching item.
$app->get('/api/admin/item', function(Request $request, Response $response){
    $model = $request->getParam('model');

    $sql = "SELECT model, itemnumber, power 
            FROM material_master_record
            WHERE model LIKE '%$model%'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all battery type for dropdown.
$app->get('/api/admin/batterytype', function(Request $request, Response $response){

    $sql = "SELECT DISTINCT battery_type 
            FROM material_master_record 
            WHERE battery_type != '' 
            ORDER BY battery_type ASC";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get item name and number for searching item.
$app->get('/api/admin/itemnumber', function(Request $request, Response $response){
    $itemnumber = $request->getParam('itemnumber');

    $sql = "SELECT model, itemnumber, power 
            FROM material_master_record
            WHERE itemnumber LIKE '%$itemnumber%'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get customer name and customer number
$app->get('/api/admin/customername', function(Request $request, Response $response){
    $customer_name = $request->getParam('customer_name');
    $sql = "SELECT customers.customer_name, customers.customer_no 
            FROM customers
            WHERE customers.customer_name LIKE '%$customer_name%' 
                OR customers.customer_eng_name LIKE '%$customer_name%'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get customer name and customer number for searching customer.
$app->get('/api/admin/customercode', function(Request $request, Response $response){
    $customer_code = $request->getParam('customer_code');
    $sql = "SELECT customers.customer_name, customers.customer_no 
            FROM customers
            WHERE customers.customer_no LIKE '%$customer_code%'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all FSE which not busy.
$app->get('/api/admin/fse', function(Request $request, Response $response){
    $sql = "SELECT engname 
            FROM fse 
            WHERE status != 'BUSY'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all site location from specified customer.
$app->get('/api/admin/location', function(Request $request, Response $response){
    $customer_no = $request->getParam('customer_no');
    $sql = "SELECT location.* 
            FROM location
            WHERE location.customer_no = '$customer_no' 
            ORDER BY sitename ASC";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Search sale order
$app->get('/api/admin/getsaleorder', function(Request $request, Response $response){
    $sale_order_no = $request->getParam('sale_order_no');

    $sql = "SELECT sale_order.* 
            FROM sale_order 
            WHERE sale_order_no LIKE '%$sale_order_no%'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});


// Send service request in customer section
$app->post('/api/customer/request', function(Request $request, Response $response){
    $sng_code = $request->getParam('sngcode');
    $name = $request->getParam('name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $problem_type = $request->getParam('problem_type');
    $problem = $request->getParam('problem');
    $request_id = $request->getParam('request_id');
    $request_username = $request->getParam('request_user');
    $request_time = date('Y-m-d H:i:s', time());
    $status = 'Pending';

    $sql = "SELECT COUNT(cm_id) FROM srm_request";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $result = json_encode($result);
        $result = json_decode($result, true);
        $current_number =  $result[0]['COUNT(cm_id)'] + 1;
        $current_number = str_pad($current_number, 4, "0", STR_PAD_LEFT);
        $cm_id = 'CM-' . date("Y") . '-' . $current_number . '-' . '0';
        
    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    $sql = "INSERT INTO srm_request (cm_id, sng_code, name, phone_number, email, problem_type, asset_problem, 
                                    request_time, request_id, request_user, job_status) 
            VALUES (:set_cm_id, :set_code, :set_name, :set_phone, :set_email, :set_problem_type, :set_problem, 
                    :set_requesttime, :set_requestid, :set_requestuser, :set_job_status)";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_cm_id', $cm_id);
        $stmt->bindParam(':set_code', $sng_code);
        $stmt->bindParam(':set_name', $name);
        $stmt->bindParam(':set_phone', $phone);
        $stmt->bindParam(':set_email', $email);
        $stmt->bindParam(':set_problem_type', $problem_type);
        $stmt->bindParam(':set_problem', $problem);
        $stmt->bindParam(':set_requesttime', $request_time);
        $stmt->bindParam(':set_requestid', $request_id);
        $stmt->bindParam(':set_requestuser', $request_username);
        $stmt->bindParam(':set_job_status', $status);
        $stmt->execute();

    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    $sql = "INSERT INTO job_fse (job_id, fse_code) VALUES ('$cm_id', '0')";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    system_log('Send SRM request with JOBID: ' . $cm_id);
    return $response->withStatus(200)->getBody()->write("SUCCESS");
});

// Add asset to customer in admin section
$app->post('/api/admin/addasset', function(Request $request, Response $response){
    $sng_code = $request->getParam('sng_code');
    $customer_no = $request->getParam('customer_no');
    $location_code = $request->getParam('location_code');
    $sale_order_no = $request->getParam('sale_order_no');
    $contactname = $request->getParam('contactname');
    $contactnumber = $request->getParam('contactnumber');
    $pmyear = $request->getParam('pmyear');
    $nextpm = $request->getParam('nextpm');
    $itemnumber = $request->getParam('itemnumber');
    $serial = $request->getParam('serial');
    $fse_code = $request->getParam('fse_code');
    $battery = $request->getParam('battery');
    $quantity = $request->getParam('quantity');
    $battery_date = $request->getParam('battery_date');
    $startwarranty = $request->getParam('startwarranty');
    $endwarranty = $request->getParam('endwarranty');
    $ups_status = $request->getParam('ups_status');
    $sla_conditon = $request->getParam('sla_condition');
    $sla_response = $request->getParam('sla_response');
    $sla_recovery = $request->getParam('sla_recovery');
    $typeofcontract = $request->getParam('toc');
    
    $sql = "INSERT INTO asset_tracker (sng_code, customer_no, contactname, contactnumber, 
                        pmyear, nextpm,itemnumber, serial, fse_code,
                        battery, quantity, battery_date, location_code,
                        startwarranty, endwarranty, ups_status, sale_order_no,
                        sla_condition, sla_response, sla_recovery, typeofcontract)
                VALUES (:set_sngcode, :set_customer_no, :set_contactname, :set_contactnumber, 
                        :set_pmyear, :set_nextpm, :set_itemnumber,
                        :set_serial, :set_fse_code, :set_battery, :set_quantity,
                        :set_battery_date, :set_location_code, :set_startwarranty,
                        :set_endwarranty, :set_ups_status, :set_sale_order_no,
                        :set_sla_condition, :set_sla_response, :set_sla_recovery, :set_typeofcontract)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_sngcode', $sng_code);
        $stmt->bindParam(':set_customer_no', $customer_no);
        $stmt->bindParam(':set_contactname', $contactname);
        $stmt->bindParam(':set_contactnumber', $contactnumber);
        $stmt->bindParam(':set_pmyear', $pmyear);
        $stmt->bindParam(':set_nextpm', $nextpm);
        $stmt->bindParam(':set_itemnumber', $itemnumber);
        $stmt->bindParam(':set_serial', $serial);
        $stmt->bindParam(':set_fse_code', $fse_code);
        $stmt->bindParam(':set_battery', $battery);
        $stmt->bindParam(':set_quantity', $quantity);
        $stmt->bindParam(':set_battery_date', $battery_date);
        $stmt->bindParam(':set_location_code', $location_code);
        $stmt->bindParam(':set_startwarranty', $startwarranty);
        $stmt->bindParam(':set_endwarranty', $endwarranty);
        $stmt->bindParam(':set_ups_status', $ups_status);
        $stmt->bindParam(':set_sale_order_no', $sale_order_no);
        $stmt->bindParam(':set_sla_condition', $sla_conditon);
        $stmt->bindParam(':set_sla_response', $sla_response);
        $stmt->bindParam(':set_sla_recovery', $sla_recovery);
        $stmt->bindParam(':set_typeofcontract', $typeofcontract);
        $stmt->execute();

        $db = null;
        system_log('Add asset with SNGCODE: ' . $sngcode);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e ) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all customers
$app->get('/api/admin/getasset', function(Request $request, Response $response){
    $sng_code = $request->getParam('sng_code');
    $sql = "SELECT asset_tracker.*, customers.customer_name, sale_order.*, material_master_record.itemnumber,
                    material_master_record.model, material_master_record.power, location.*
            FROM asset_tracker, customers, sale_order, material_master_record, location
            WHERE asset_tracker.sng_code = '$sng_code'
            AND asset_tracker.customer_no = customers.customer_no
            AND asset_tracker.sale_order_no = sale_order.sale_order_no
            AND asset_tracker.itemnumber = material_master_record.itemnumber
            AND asset_tracker.location_code  = location.location_code";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);
    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add ticket
$app->post('/api/admin/addticket', function(Request $request, Response $response){

    $sql = "SELECT COUNT(cm_id) FROM srm_request";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $result = json_encode($result);
        $result = json_decode($result, true);
        $current_number =  $result[0]['COUNT(cm_id)'] + 1;
        $current_number = str_pad($current_number, 4, "0", STR_PAD_LEFT);
        $cm_id = 'CM-' . date("Y") . '-' . $current_number . '-' . '0';
        
    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    $sng_code  = $request->getParam('sng_code');
    $name = $request->getParam('name');
    $phone_number = $request->getParam('phone_number');
    $email = $request->getParam('email');
    $problem_type = $request->getParam('problem_type');
    $asset_problem = $request->getParam('asset_problem');
    $asset_detected = $request->getParam('asset_detected');
    $solution  = $request->getParam('solution');
    $suggestions = $request->getParam('suggestions');
    $cause_id = $request->getParam('cause_id');
    $correction_id = $request->getParam('correction_id');
    $ups_status = $request->getParam('ups_status');
    $cause_detail = $request->getParam('cause_detail');
    $correction_detail = $request->getParam('correction_detail');
    $fse_code = $request->getParam('fse_code');
    $cm_time = $request->getParam('cm_time');
    $complete_time = $request->getParam('complete_time');
    $job_type = $request->getParam('job_type');
    $work_class = 'CM';
    $request_time = date('Y-m-d H:i:s', time());
    $start_time = $request->getParam('start_time');
    $close_time = $request->getParam('close_time');
    $leader = $request->getParam('leader');
    $request_id = $_SESSION['account_no'];
    $request_user = $_SESSION['username_unhash'];

    if ($job_type != 'Fixed by phone') {
        if ($complete_time != '' && $fse_code != 0 && $cm_time != '' && $job_type != '') $job_status = 'Pending Approve';
        elseif ($complete_time == '' && $fse_code != 0 && $cm_time != '' && $job_type != '') $job_status = 'Assigned';
        else $job_status = 'Pending';
    } else {
        $complete_time = $close_time;
        $job_status = "Closed";
    }

    $sql = "SELECT sng_code FROM asset_tracker WHERE sng_code = '$sng_code' LIMIT 1";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$result){
            return '1';
        }

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
 
    
    $sql = "INSERT INTO srm_request (cm_id, sng_code, name, email, phone_number, problem_type,
                asset_problem, asset_detected, solution, suggestions, cause_id, correction_id,
                cm_time, job_status, complete_time, request_time, cause_detail, correction_detail,
                work_class, job_type, close_time, start_time, request_id, request_user)
            VALUES
                (:set_cm_id, :set_sngcode, :set_name, :set_email, :set_phone_number,
                :set_problem_type, :set_asset_problem, :set_asset_detected, :set_solution,
                :set_suggestions, :set_cause_id, :set_correction_id, :set_cm_time, :set_job_status,
                :set_complete_time, :set_request_time, :set_cause_detail, :set_correction_detail,
                :set_work_class, :set_job_type, :set_close_time, :set_start_time, :set_request_id,
                :set_request_user)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_cm_id', $cm_id);
        $stmt->bindParam(':set_sngcode', $sng_code);
        $stmt->bindParam(':set_name', $name);
        $stmt->bindParam(':set_email', $email);
        $stmt->bindParam(':set_phone_number', $phone_number);
        $stmt->bindParam(':set_problem_type', $problem_type);
        $stmt->bindParam(':set_asset_problem', $asset_problem);
        $stmt->bindParam(':set_asset_detected', $asset_detected);
        $stmt->bindParam(':set_solution', $solution);
        $stmt->bindParam(':set_suggestions', $suggestions);
        $stmt->bindParam(':set_cause_id', $cause_id);
        $stmt->bindParam(':set_correction_id', $correction_id);
        $stmt->bindParam(':set_cm_time', $cm_time);
        $stmt->bindParam(':set_job_status', $job_status);
        $stmt->bindParam(':set_complete_time', $complete_time);
        $stmt->bindParam(':set_request_time', $request_time);
        $stmt->bindParam(':set_cause_detail', $cause_detail);
        $stmt->bindParam(':set_correction_detail', $correction_detail);
        $stmt->bindParam(':set_work_class', $work_class);
        $stmt->bindParam(':set_job_type', $job_type);
        $stmt->bindParam(':set_close_time', $close_time);
        $stmt->bindParam(':set_start_time', $start_time);
        $stmt->bindParam(':set_request_id', $request_id);
        $stmt->bindParam(':set_request_user', $request_user);
        $stmt->execute();

    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($fse_code != 0) {
        if ($job_type != 'Fixed by phone') {
        $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ";
            foreach ($fse_code as $code=>$value) {
                if ($value == $leader) $is_leader = 1;
                else $is_leader = 0; 
                $sql = $sql . "('$cm_id', '$value', '$is_leader'),";
            }

            $last_char = strlen($sql) - 1;
            $sql[$last_char] = ";";
        } else {
            $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ('$cm_id', '$fse_code', '0')";
        }
    } else { 
        $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ('$cm_id', '0', '0')";
    }

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();

    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($fse_code != 0 && $cm_time != '' && $job_type != '' && $complete_time == '') {
        $sql1 = "SELECT thainame, email, fse_code, username FROM fse WHERE fse_code IN (";

        foreach ($fse_code as $code=>$value){
            $sql1 = $sql1 . $value . ',';
        }
        $last_char = strlen($sql1) - 1;
        $sql1[$last_char] = ")";

        $sql2 = "SELECT location.*, asset_tracker.*, material_master_record.* FROM location, asset_tracker, material_master_record
        WHERE sng_code = '$sng_code'
        AND location.location_code = asset_tracker.location_code
        AND asset_tracker.itemnumber = material_master_record.itemnumber";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt1 = $db->query($sql1);
            $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            $stmt2 = $db->query($sql2);
            $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
            $result2 = json_encode($result2);
            $result2 = json_decode($result2, true);

            foreach($result1 as $row) {
                $subject = 'Syngergize SRM';
                $body = '<b>เรียนคุณ ' . $row['thainame'] . '</b><br>' .
                '<b>รายละเอียดงาน CM ของคุณมีดังนี้</b><br>'.
                'เลขที่ CM: ' . $cm_id . '<br>'.
                'ชนิดงาน: ' . $job_type . '<br>'.
                '----------ข้อมูลสินค้า----------<br>'.
                'สินค้า: ' . $result2['model'] . '<br>'.
                'Rate: ' . $result2['power'] . '<br>'.
                'ชนิดแบตเตอรี่: ' . $result2['battery'] . '<br>'.
                'จำนวนแบตเตอรี่: ' . $result2['quantity'] . '<br>'.
                'วันที่ติดตั้งแบตเตอรี่: ' . $result2['battery_date'] . '<br>'.
                '----------ข้อมูลปัญหา----------<br>'.
                'ชนิดของปัญหา: ' . $problem_type . '<br>'.
                'รายละเอียด: ' . $asset_problem . '<br>'.
                'สถานะ: ' . $result2['ups_status'] . '<br>'.
                '----------สถานที่----------<br>'.
                'ชื่อสถานที่: ' . $result2['sitename'] . '<br>'.
                'เลขที่: ' . $result2['house_no'] . '<br>'.
                'หมู่ที่: ' . $result2['village_no'] . '<br>'.
                'ถนน: ' . $result2['road'] . '<br>'.
                'ตำบล/แขวง: ' . $result2['sub_district'] . '<br>'.
                'อำเภอ/เขต: ' . $result2['district'] . '<br>'.
                'จังหวัด: ' . $result2['province'] . '<br>'.
                'ประเทศ: ' . $result2['country'] . '<br>'.
                '----------เวลา----------<br>'.
                'เวลา: ' . $cm_time . '<br>'.
                '----------ติดต่อ----------<br>'.
                'ชื่อ: ' . $name . '<br>'.
                'เบอร์ติดต่อ: ' . $phone_number . '<br><br>'.
                '<b>หากคุณรับทราบแล้วให้คลิกที่ลิงค์ด้านล่างนี้(อ่านรายละเอียดก่อนคลิก)</b><br>'.
                '<a href="192.168.1.248/srmsng/public/fse">โปรดคลิกที่ลิงค์นี้เพื่อตอบรับ</a><br>'.
                'บริษัท ซินเนอร์ไจซ์ โปรไวด์ เซอร์วิส จำกัด<br>'.
                'Synergize Provide Service Co., Ltd.<br>'.
                '31/14 หมู่ 10 ต.ลาดสวาย อ.ลำลูกกา จ.ปทุมธานี 12150<br>'.
                '31/14 Moo 10, T.Ladsawai, A.Lamlukka, Pathumthani 12150<br>'.
                'Mobile phone: +668 7585 8635<br>'.
                'Tel  : +662 157 1325<br>'.
                'Fax : +662 157 1328<br>'.
                '<a href="url">www.synergize-th.com</a><br>'.
                '<a href="url">poowapong@synergize.co.th</a><br>';
                $res = smtp($subject, $body, $row['email']);
            }
            $db = null;
        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    }

    $sql = "SELECT cm_id FROM srm_request WHERE sng_code = '$sng_code' AND request_time = '$request_time' LIMIT 1";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        system_log('Add ticket with JOBID: ' . $result->cm_id);
        $db = null;
        return $response->withStatus(200)->getBody()->write("SUCCESS");
    

    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Update ticket status to acknowledge
$app->put('/api/fse/acknowledge', function(Request $request, Response $response){
    $job_status = 'Acknowledged';
    $engname = $request->getParam('engname');
    $cm_id = $request->getParam('cm_id');

    $sql = "SELECT job_status FROM srm_request WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($result['job_status'] == 'Assigned'){

        $sql = "UPDATE srm_request SET
                    job_status = :set_job_status
                WHERE cm_id = '$cm_id'";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->execute();
            $action = 'Acknowledge CM id :';
            system_log($engname . $action . $cm_id);
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});

// Update ticket status to travelling
$app->put('/api/fse/starttravel', function(Request $request, Response $response){
    $job_status = 'Travelling';
    $engname = $request->getParam('engname');
    $cm_id = $request->getParam('cm_id');
    $start_travel_time = date('Y-m-d H:i:s', time());
    $sng_code = $request->getParam('sng_code');
    // return getClient();

    try {
        $username = $_SESSION['username_unhash'];
        $sql = "SELECT fse_code FROM fse WHERE username = '$username'";
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $result =  $stmt->fetch(PDO::FETCH_OBJ);
            $result = json_encode($result);
            $result = json_decode($result, true);
            
            $db = null;
        }catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
        $fse_code = $result['fse_code'];
        $location_info = json_decode(get_fse_location($fse_code));
        $lat = $location_info->{'latitude'};
        $lon = $location_info->{'longitude'};

        $sql = "SELECT latitude, longitude 
                FROM location, asset_tracker 
                WHERE asset_tracker.sng_code = '$sng_code'
                    AND location.location_code = asset_tracker.location_code";
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $result =  $stmt->fetch(PDO::FETCH_OBJ);
            $result = json_encode($result);
            $result = json_decode($result, true);
            
            $db = null;
        }catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }

        $latitude =  $result['latitude'];
        $longitude = $result['longitude'];

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$lat,$lon&destinations=$latitude,$longitude&key=AIzaSyBjTkocs5c2JDi5kyVXOLfT3PG9Uh6eXLw";
    
        $data = json_decode(file_get_contents($url));

        $distance = json_encode($data->{'rows'}[0]->{'elements'}[0]->{'distance'}->{'value'} / 1000);
        $distance_time = json_encode(ceil($data->{'rows'}[0]->{'elements'}[0]->{'duration'}->{'value'} / 60));
    
    } catch(Exception $e) {
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    $sql = "SELECT job_status FROM srm_request WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($result['job_status'] == 'Acknowledged'){

        $sql = "UPDATE srm_request SET
                    job_status        = :set_job_status,
                    start_travel_time = :set_start_travel_time,
                    distance          = :set_distance,
                    distance_time     = :set_distance_time
                WHERE cm_id = '$cm_id'";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->bindParam(':set_start_travel_time', $start_travel_time);
            $stmt->bindParam(':set_distance', $distance);
            $stmt->bindParam(':set_distance_time', $distance_time);
            $stmt->execute();
            $action = 'Start Travelling CM id :';
            system_log($engname . $action . $cm_id);
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});

// Update ticket status to arrived site.
$app->put('/api/fse/arrivedsite', function(Request $request, Response $response){
    $job_status = 'Arrived';
    $engname = $request->getParam('engname');
    $cm_id = $request->getParam('cm_id');
    $sng_code = $request->getParam('sng_code');
    $arrived_time = date('Y-m-d H:i:s', time());

    try {
        $username = $_SESSION['username_unhash'];
        $sql = "SELECT fse_code FROM fse WHERE username = '$username'";
        try {
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $result =  $stmt->fetch(PDO::FETCH_OBJ);
            $result = json_encode($result);
            $result = json_decode($result, true);
            
            $db = null;
        }catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }

        $fse_code = $result['fse_code'];
        $location_info = json_decode(get_fse_location($fse_code));
        $lat = $location_info->{'latitude'};
        $lon = $location_info->{'longitude'};

        $sql = "SELECT latitude, longitude 
                FROM location, asset_tracker 
                WHERE asset_tracker.sng_code = '$sng_code'
                    AND location.location_code = asset_tracker.location_code";
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $result =  $stmt->fetch(PDO::FETCH_OBJ);
            $result = json_encode($result);
            $result = json_decode($result, true);
            
            $db = null;
        }catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }

        $latitude =  $result['latitude'];
        $longitude = $result['longitude'];

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$lat,$lon&destinations=$latitude,$longitude&key=AIzaSyBjTkocs5c2JDi5kyVXOLfT3PG9Uh6eXLw";
    
        $data = json_decode(file_get_contents($url));
        
        $distance = json_encode($data->{'rows'}[0]->{'elements'}[0]->{'distance'}->{'value'} / 1000);
        
        } catch(Exception $e) {
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }

    if ($distance > 1.0) {
        $text = array('response' => "You have to be near site atleast 1 KM.");
        return $response->withJson($text, 400);
    }
    
    $sql = "SELECT job_status FROM srm_request WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($result['job_status'] == 'Travelling'){

        $sql = "UPDATE srm_request SET
                    job_status = :set_job_status,
                    arrived_time = :set_arrived_time
                WHERE cm_id = '$cm_id'";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->bindParam(':set_arrived_time', $arrived_time);
            $stmt->execute();
            $action = 'Arrived Site CM id :';
            system_log($engname . $action . $cm_id);
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});

// Update ticket status to Working in
$app->put('/api/fse/startwork', function(Request $request, Response $response){
    $job_status = 'Working in Progress';
    $engname = $request->getParam('engname');
    $cm_id = $request->getParam('cm_id');
    $start_time = date('Y-m-d H:i:s', time());

    $sql = "SELECT job_status FROM srm_request WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($result['job_status'] == 'Arrived'){

        $sql = "UPDATE srm_request SET
                    job_status    = :set_job_status,
                    start_time    = :set_start_time
                WHERE cm_id = '$cm_id'";

        try {
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->bindParam(':set_start_time', $start_time);

            $stmt->execute();
            $action = 'Start Work CM id :';
            system_log($engname . $action . $cm_id);
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});

// Update ticket status to
$app->put('/api/fse/notfinishwork', function(Request $request, Response $response){
    $job_status = 'Incomplete Pending Approve';
    $notes = $request->getParam('notes');
    $cm_id = $request->getParam('cm_id');
    $complete_time = date('Y-m-d H:i:s', time());
    $is_finish = false;

    $sql = "SELECT job_status FROM srm_request WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    if ($result['job_status'] == 'Working in Progress'){
        $sql = "UPDATE srm_request SET
                    job_status    = :set_job_status,
                    complete_time = :set_complete_time,
                    is_finish     = :set_is_finish,
                    notes         = :set_notes
                WHERE cm_id = '$cm_id'";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->bindParam(':set_complete_time', $complete_time);
            $stmt->bindParam(':set_is_finish', $is_finish);
            $stmt->bindParam(':set_notes', $notes);
            $stmt->execute();
            $action = 'Work Incomplete CM id :';
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});

// Update ticket status to Working in
$app->put('/api/fse/finishwork', function(Request $request, Response $response){
    $job_status = 'Pending Approve';
    $engname = $request->getParam('engname');
    $cm_id = $request->getParam('cm_id');
    $complete_time = date('Y-m-d H:i:s', time());
    $is_finish = true;

    $sql = "SELECT job_status 
            FROM srm_request 
            WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    // return 'abc';
    if ($result['job_status'] == 'Working in Progress'){

        $sql = "UPDATE srm_request SET
                    job_status    = :set_job_status,
                    complete_time = :set_complete_time,
                    is_finish     = :set_is_finish
                WHERE cm_id = '$cm_id'";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->bindParam(':set_complete_time', $complete_time);
            $stmt->bindParam(':set_is_finish', $is_finish);
            $stmt->execute();
            $action = 'Work Complete CM id :';
            system_log($engname . $action . $cm_id);
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});


$app->put('/api/fse/deny', function(Request $request, Response $response){
    $job_status = 'Pending';
    $engname = $request->getParam('engname');
    $cm_id = $request->getParam('cm_id');

    $sql = "SELECT job_status 
            FROM srm_request 
            WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($result['job_status'] == 'Assigned'){
        $sql = "DELETE FROM job_fse WHERE job_id = '$cm_id'";
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
    
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $db = null;
    
        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }

        $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ('$cm_id', '0', '0')";
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->execute();
    
        }catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }


        $sql = "UPDATE srm_request SET
                    job_status    = :set_job_status
                WHERE cm_id = '$cm_id'";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->execute();
            $action = 'Work Complete CM id :';
            system_log($engname . $action . $cm_id);
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "You have no permission to reject this work.";
    }
});

// Verify Incomplete
$app->put('/api/admin/reject', function(Request $request, Response $response){
    $cm_id  = $request->getParam('cm_id');
    $status  = $request->getParam('status');
    
    $sql = "SELECT job_status, complete_time 
            FROM srm_request 
            WHERE cm_id = '$cm_id'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($result['job_status'] == 'Incomplete Pending Approve' || 
    $result['job_status'] == 'Pending Approve'){

        $sql = "UPDATE srm_request SET
                    job_status    = :set_job_status,
                    complete_time = :set_complete_time,
                    is_finish     = :set_is_finish
                WHERE cm_id = '$cm_id'";
        
        if ($status == 'Incomplete Pending Approve') { 
            $job_status = "Completed";
            $is_finish = true;
            $complete_time = $result['complete_time'];
       } elseif ($status == 'Pending Approve') {
            $job_status = 'Working in Progress';
            $complete_time = '';
            $is_finish = false;
       } else {
           return; 
       }

        try {
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->bindParam(':set_complete_time', $complete_time);
            $stmt->bindParam(':set_is_finish', $is_finish);
            $stmt->execute();
            $action = 'Reject CM id :';
            system_log($action . $cm_id);
            $db = null;
            return $response->withStatus(200)->getBody()->write("SUCCESS");

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});

// Approve CM
$app->put('/api/admin/approvecm', function(Request $request, Response $response){
    $cm_id = $request->getParam('cm_id');
    $approve_time = date('Y-m-d H:i:s', time());
    $sql = "SELECT job_status 
            FROM srm_request 
            WHERE cm_id = '$cm_id'";
    
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result =  $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        $db = null;
    
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($result['job_status'] == 'Pending Approve' || $result['job_status'] == 'Incomplete Pending Approve'){
        
        if ($result['job_status'] == 'Pending Approve') { 
            $job_status = "Completed";
            $is_finish = true;
       } elseif ($result['job_status'] == 'Incomplete Pending Approve') {
            $job_status = 'Incomplete';
            $is_finish = false;
       }
        $sql = "UPDATE srm_request SET
                    job_status   = :set_job_status,
                    approve_time = :set_approve_time
                WHERE cm_id = '$cm_id'";

        try {
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_job_status', $job_status);
            $stmt->bindParam(':set_approve_time', $approve_time);
            $stmt->execute();
            $action = 'Approve CM id :';
            system_log($action . $cm_id);
            $db = null;
            if ($result['job_status'] == 'Pending Approve') return "SUCCESS";
        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }

        if ($result['job_status'] == 'Incomplete Pending Approve'){
            $old_cm = implode("-", array_slice(explode("-", $cm_id), 0, 3));

            $sql = "SELECT COUNT(cm_id) FROM srm_request WHERE cm_id LIKE '$old_cm%'";
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $db = null;
                $result = json_encode($result);
                $result = json_decode($result, true);
                $current_number =  $result[0]['COUNT(cm_id)'];
                $cm_id_new = $old_cm . "-" . $current_number;
            } catch(PDOException $e) {
                $db = null;
                return $response->withStatus(400)->getBody()->write($e->getmessage());
            }

            $sql = "SELECT sng_code, name, phone_number, srm_request.email, problem_type,
                            asset_problem, asset_detected, solution, suggestions, cause_id,
                            correction_id, ups_status, cause_detail, correction_detail
                    FROM srm_request
                    WHERE srm_request.cm_id = '$cm_id'";

            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);
                $result =  $stmt->fetch(PDO::FETCH_OBJ);
                $result = json_encode($result);
                $result = json_decode($result, true);
                $db = null;
            } catch(PDOException $e) {
                $db = null;
                return $response->withStatus(400)->getBody()->write($e->getmessage());
            }

            $sng_code = $result['sng_code'];
            $name = $result['name'];
            $phone_number = $result['phone_number'];
            $email = $result['email'];
            $problem_type = $result['problem_type'];
            $asset_problem = $result['asset_problem'];
            $asset_detected = $result['asset_detected'];
            $solution = $result['solution'];
            $suggestions = $result['suggestions'];
            $cause_id = $result['cause_id'];
            $correction_id = $result['correction_id'];
            $ups_status = $result['ups_status'];
            $cause_detail = $result['cause_detail'];
            $correction_detail = $result['correction_detail'];
            $job_status = 'Pending';
            $work_class = 'CM';

            $sql = "INSERT INTO srm_request (cm_id, sng_code, name, email, phone_number, problem_type,
                                asset_problem, asset_detected, solution, suggestions, cause_id, correction_id,
                                job_status, request_time, cause_detail, correction_detail, work_class)
                    VALUES  (:set_new_cm_id, :set_sng_code, :set_name, :set_email, :set_phone_number,
                            :set_problem_type, :set_asset_problem, :set_asset_detected, :set_solution,
                            :set_suggestions, :set_cause_id, :set_correction_id, :set_job_status,
                            :set_request_time, :set_cause_detail, :set_correction_detail, :set_work_class)";

            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':set_new_cm_id', $cm_id_new);
                $stmt->bindParam(':set_sng_code', $sng_code);
                $stmt->bindParam(':set_name', $name);
                $stmt->bindParam(':set_email', $email);
                $stmt->bindParam(':set_phone_number', $phone_number);
                $stmt->bindParam(':set_problem_type', $problem_type);
                $stmt->bindParam(':set_asset_problem', $asset_problem);
                $stmt->bindParam(':set_asset_detected', $asset_detected);
                $stmt->bindParam(':set_solution', $solution);
                $stmt->bindParam(':set_suggestions', $suggestions);
                $stmt->bindParam(':set_cause_id', $cause_id);
                $stmt->bindParam(':set_correction_id', $correction_id);
                $stmt->bindParam(':set_job_status', $job_status);
                $stmt->bindParam(':set_request_time', $approve_time);
                $stmt->bindParam(':set_cause_detail', $cause_detail);
                $stmt->bindParam(':set_correction_detail', $correction_detail);
                $stmt->bindParam(':set_work_class', $work_class);
                $stmt->execute();
                $db = null;

            } catch(PDOException $e){
                $db = null;
                return $response->withStatus(400)->getBody()->write($e->getmessage());
            }

            $sql = "INSERT INTO job_fse (job_id, fse_code)
                    VALUES  ('$cm_id_new', '0')";

            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $db = null;
                return $response->withStatus(200)->getBody()->write("SUCCESS");
            } catch(PDOException $e){
                $db = null;
                return $response->withStatus(400)->getBody()->write($e->getmessage());
            }
        }
    } else {
        return "Some of your team have been response to this message.";
    }
});


// Update ticket
$app->put('/api/admin/assignticketonmap', function(Request $request, Response $response){
    $cm_id = $request->getParam('cm_id');
    $fse_code = $request->getParam('fse_code');
    $cm_time = $request->getParam('cm_time');
    $leader = $request->getParam('leader');
    $job_type = 'On site';
    $complete_time = '';
    $sng_code = $request->getParam('sng_code');

    $sql = "DELETE FROM job_fse WHERE job_id = '$cm_id'";
 
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
  
    if ($fse_code != 0) {
        $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ";
        foreach ($fse_code as $code=>$value) {
            if ($value == $leader) $is_leader = 1;
            else $is_leader = 0; 
            $sql = $sql . "('$cm_id', '$value', '$is_leader'),";
        }
        $last_char = strlen($sql) - 1;
        $sql[$last_char] = ";";

    } else { 
        $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ('$cm_id', '0', '0')";
    }
    
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($complete_time != '' && $fse_code != 0 && $cm_time != '' && $job_type != '') $job_status = 'Pending Approve';
    elseif ($complete_time == '' && $fse_code != 0 && $cm_time != '' && $job_type != '') $job_status = 'Assigned';
    else $job_status = 'Pending';

    $sql = "UPDATE srm_request SET
                cm_time           = :set_cm_time,
                job_status        = :set_job_status,
                job_type          = :set_job_type
            WHERE cm_id = '$cm_id'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':set_cm_time', $cm_time);
        $stmt->bindParam(':set_job_status', $job_status);
        $stmt->bindParam(':set_job_type', $job_type);

        $stmt->execute();

        system_log('Assign ticket at JOBID: ' . $cm_id);
        $db = null;
        
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($fse_code != 0 && $cm_time != '' && $job_type != '' && $complete_time == '') {
        $sql1 = "SELECT thainame, email, fse_code, username FROM fse WHERE fse_code IN (";

        foreach ($fse_code as $code=>$value){
            $sql1 = $sql1 . $value . ',';
        }
        $last_char = strlen($sql1) - 1;
        $sql1[$last_char] = ")";

        $sql2 = "SELECT location.*, asset_tracker.*, material_master_record.* 
                FROM location, asset_tracker, material_master_record
                WHERE sng_code = '$sng_code'
                    AND location.location_code = asset_tracker.location_code
                    AND asset_tracker.itemnumber = material_master_record.itemnumber";

        $sql3 = "SELECT problem_type, asset_problem, name, phone_number
                FROM srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse
                WHERE asset_tracker.sng_code    = srm_request.sng_code
                    AND location.location_code    = asset_tracker.location_code
                    AND asset_tracker.itemnumber  = material_master_record.itemnumber
                    AND srm_request.cm_id         = '$cm_id'
                    AND job_fse.job_id            = '$cm_id'
                    AND fse.fse_code              = job_fse.fse_code
                    AND srm_request.cause_id      = root_cause.cause_id
                    AND srm_request.correction_id = correction.correction_id
                    AND srm_request.job_status    != 'Closed'
                    AND srm_request.job_status    != 'Completed'
                GROUP BY srm_request.cm_id";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt1 = $db->query($sql1);
            $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            $stmt2 = $db->query($sql2);
            $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
            $result2 = json_encode($result2);
            $result2 = json_decode($result2, true);

            $stmt3 = $db->query($sql3);
            $result3 = $stmt3->fetch(PDO::FETCH_OBJ);
            $result3 = json_encode($result3);
            $result3 = json_decode($result3, true);

            foreach($result1 as $row) {
                $subject = 'Syngergize SRM';
                $body = '<b>เรียนคุณ ' . $row['thainame'] . '</b><br>' .
                '<b>รายละเอียดงาน CM ของคุณมีดังนี้</b><br>'.
                'เลขที่ CM: ' . $cm_id . '<br>'.
                'ชนิดงาน: ' . $job_type . '<br>'.
                '----------ข้อมูลสินค้า----------<br>'.
                'สินค้า: ' . $result2['model'] . '<br>'.
                'Rate: ' . $result2['power'] . '<br>'.
                'ชนิดแบตเตอรี่: ' . $result2['battery'] . '<br>'.
                'จำนวนแบตเตอรี่: ' . $result2['quantity'] . '<br>'.
                'วันที่ติดตั้งแบตเตอรี่: ' . $result2['battery_date'] . '<br>'.
                '----------ข้อมูลปัญหา----------<br>'.
                'ชนิดของปัญหา: ' . $result3['problem_type'] . '<br>'.
                'รายละเอียด: ' . $result3['asset_problem'] . '<br>'.
                'สถานะ: ' . $result2['ups_status'] . '<br>'.
                '----------สถานที่----------<br>'.
                'ชื่อสถานที่: ' . $result2['sitename'] . '<br>'.
                'เลขที่: ' . $result2['house_no'] . '<br>'.
                'หมู่ที่: ' . $result2['village_no'] . '<br>'.
                'ถนน: ' . $result2['road'] . '<br>'.
                'ตำบล/แขวง: ' . $result2['sub_district'] . '<br>'.
                'อำเภอ/เขต: ' . $result2['district'] . '<br>'.
                'จังหวัด: ' . $result2['province'] . '<br>'.
                'ประเทศ: ' . $result2['country'] . '<br>'.
                '----------เวลา----------<br>'.
                'เวลา: ' . $cm_time . '<br>'.
                '----------ติดต่อ----------<br>'.
                'ชื่อ: ' . $result3['name'] . '<br>'.
                'เบอร์ติดต่อ: ' . $result3['phone_number'] . '<br><br>'.
                '<b>หากคุณรับทราบแล้วให้คลิกที่ลิงค์ด้านล่างนี้(อ่านรายละเอียดก่อนคลิก)</b><br>'.
                '<a href="192.168.1.248/srmsng/public/fse">โปรดคลิกที่ลิงค์นี้เพื่อตอบรับ</a><br>'.
                'บริษัท ซินเนอร์ไจซ์ โปรไวด์ เซอร์วิส จำกัด<br>'.
                'Synergize Provide Service Co., Ltd.<br>'.
                '31/14 หมู่ 10 ต.ลาดสวาย อ.ลำลูกกา จ.ปทุมธานี 12150<br>'.
                '31/14 Moo 10, T.Ladsawai, A.Lamlukka, Pathumthani 12150<br>'.
                'Mobile phone: +668 7585 8635<br>'.
                'Tel  : +662 157 1325<br>'.
                'Fax : +662 157 1328<br>'.
                '<a href="url">www.synergize-th.com</a><br>'.
                '<a href="url">poowapong@synergize.co.th</a><br>';
                $res = smtp($subject, $body, $row['email']);
            }
            return $response->withStatus(200)->getBody()->write("SUCCESS");
            $db = null;

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    }
});






// Update ticket
$app->put('/api/admin/assignticket', function(Request $request, Response $response){
    $cm_id = $request->getParam('cm_id');
    $name = $request->getParam('name');
    $phone_number = $request->getParam('phone_number');
    $email = $request->getParam('email');
    $sng_code = $request->getParam('sng_code');
    $fse_code = $request->getParam('fse_code');
    $job_type = $request->getParam('job_type');
    $problem_type = $request->getParam('problem_type');
    $asset_problem = $request->getParam('asset_problem');
    $asset_detected = $request->getParam('asset_detected');
    $suggestions = $request->getParam('suggestions');
    $correction_id =  $request->getParam('correction_id');
    $cause_detail =  $request->getParam('cause_detail');
    $correction_detail =  $request->getParam('correction_detail');
    $solution  = $request->getParam('solution');
    $ups_status = $request->getParam('ups_status');
    $cause_id = $request->getParam('cause_id');
    $cm_time = $request->getParam('cm_time');
    $complete_time = $request->getParam('complete_time');
    $start_time = $request->getParam('start_time');
    $close_time = $request->getParam('close_time');
    $leader = $request->getParam('leader');

    $sql = "DELETE FROM job_fse WHERE job_id = '$cm_id'";
 

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
  
    if ($fse_code != 0) {
        if ($job_type != 'Fixed by phone') {
        $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ";
            foreach ($fse_code as $code=>$value) {
                if ($value == $leader) $is_leader = 1;
                else $is_leader = 0; 
                $sql = $sql . "('$cm_id', '$value', '$is_leader'),";
            }

            $last_char = strlen($sql) - 1;
            $sql[$last_char] = ";";
        } else {
            $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ('$cm_id', '$fse_code', '0')";
        }
    } else { 
        $sql = "INSERT INTO job_fse (job_id, fse_code, is_leader) VALUES ('$cm_id', '0', '0')";
    }
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();

    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($job_type != 'Fixed by phone')
    {
        if ($complete_time != '' && $fse_code != 0 && $cm_time != '' && $job_type != '') $job_status = 'Pending Approve';
        elseif ($complete_time == '' && $fse_code != 0 && $cm_time != '' && $job_type != '') $job_status = 'Assigned';
        else $job_status = 'Pending';
    } else {
        $complete_time = $close_time;
        $job_status = "Closed";
    }

    $sql = "UPDATE srm_request SET
                name              = :set_name,
                phone_number      = :set_phone_number,
                email             = :set_email,
                job_status        = :set_job_status,
                problem_type      = :set_problem_type,
                asset_problem     = :set_asset_problem,
                asset_detected    = :set_asset_detected,
                suggestions       = :set_suggestions,
                cause_id          = :set_cause_id,
                correction_id     = :set_correction_id,
                cause_detail      = :set_cause_detail,
                correction_detail = :set_correction_detail,
                solution          = :set_solution,
                ups_status        = :set_ups_status,
                cm_time           = :set_cm_time,
                job_type          = :set_job_type,
                complete_time     = :set_complete_time,
                close_time        = :set_close_time,
                start_time        = :set_start_time
            WHERE cm_id = '$cm_id'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_name', $name);
        $stmt->bindParam(':set_phone_number', $phone_number);
        $stmt->bindParam(':set_email', $email);
        $stmt->bindParam(':set_job_status', $job_status);
        $stmt->bindParam(':set_problem_type', $problem_type);
        $stmt->bindParam(':set_asset_problem', $asset_problem);
        $stmt->bindParam(':set_asset_detected', $asset_detected);
        $stmt->bindParam(':set_suggestions', $suggestions);
        $stmt->bindParam(':set_cause_id', $cause_id);
        $stmt->bindParam(':set_correction_id', $correction_id);
        $stmt->bindParam(':set_cause_detail', $cause_detail);
        $stmt->bindParam(':set_correction_detail', $correction_detail);
        $stmt->bindParam(':set_solution', $solution);
        $stmt->bindParam(':set_ups_status', $ups_status);
        $stmt->bindParam(':set_cm_time', $cm_time);
        $stmt->bindParam(':set_complete_time', $complete_time);
        $stmt->bindParam(':set_job_type', $job_type);
        $stmt->bindParam(':set_close_time', $close_time);
        $stmt->bindParam(':set_start_time', $start_time);
        $stmt->execute();

        system_log('Assign ticket at JOBID: ' . $cm_id);
        $db = null;
        
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    if ($fse_code != 0 && $cm_time != '' && $job_type != '' && $complete_time == '') {
        $sql1 = "SELECT thainame, email, fse_code, username FROM fse WHERE fse_code IN (";

        foreach ($fse_code as $code=>$value){
            $sql1 = $sql1 . $value . ',';
        }
        $last_char = strlen($sql1) - 1;
        $sql1[$last_char] = ")";

        $sql2 = "SELECT location.*, asset_tracker.*, material_master_record.* 
                FROM location, asset_tracker, material_master_record
                WHERE sng_code = '$sng_code'
                    AND location.location_code = asset_tracker.location_code
                    AND asset_tracker.itemnumber = material_master_record.itemnumber";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt1 = $db->query($sql1);
            $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            $stmt2 = $db->query($sql2);
            $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
            $result2 = json_encode($result2);
            $result2 = json_decode($result2, true);

            foreach($result1 as $row) {
                $subject = 'Syngergize SRM';
                $body = '<b>เรียนคุณ ' . $row['thainame'] . '</b><br>' .
                '<b>รายละเอียดงาน CM ของคุณมีดังนี้</b><br>'.
                'เลขที่ CM: ' . $cm_id . '<br>'.
                'ชนิดงาน: ' . $job_type . '<br>'.
                '----------ข้อมูลสินค้า----------<br>'.
                'สินค้า: ' . $result2['model'] . '<br>'.
                'Rate: ' . $result2['power'] . '<br>'.
                'ชนิดแบตเตอรี่: ' . $result2['battery'] . '<br>'.
                'จำนวนแบตเตอรี่: ' . $result2['quantity'] . '<br>'.
                'วันที่ติดตั้งแบตเตอรี่: ' . $result2['battery_date'] . '<br>'.
                '----------ข้อมูลปัญหา----------<br>'.
                'ชนิดของปัญหา: ' . $problem_type . '<br>'.
                'รายละเอียด: ' . $asset_problem . '<br>'.
                'สถานะ: ' . $result2['ups_status'] . '<br>'.
                '----------สถานที่----------<br>'.
                'ชื่อสถานที่: ' . $result2['sitename'] . '<br>'.
                'เลขที่: ' . $result2['house_no'] . '<br>'.
                'หมู่ที่: ' . $result2['village_no'] . '<br>'.
                'ถนน: ' . $result2['road'] . '<br>'.
                'ตำบล/แขวง: ' . $result2['sub_district'] . '<br>'.
                'อำเภอ/เขต: ' . $result2['district'] . '<br>'.
                'จังหวัด: ' . $result2['province'] . '<br>'.
                'ประเทศ: ' . $result2['country'] . '<br>'.
                '----------เวลา----------<br>'.
                'เวลา: ' . $cm_time . '<br>'.
                '----------ติดต่อ----------<br>'.
                'ชื่อ: ' . $name . '<br>'.
                'เบอร์ติดต่อ: ' . $phone_number . '<br><br>'.
                '<b>หากคุณรับทราบแล้วให้คลิกที่ลิงค์ด้านล่างนี้(อ่านรายละเอียดก่อนคลิก)</b><br>'.
                '<a href="192.168.1.248/srmsng/public/fse">โปรดคลิกที่ลิงค์นี้เพื่อตอบรับ</a><br>'.
                'บริษัท ซินเนอร์ไจซ์ โปรไวด์ เซอร์วิส จำกัด<br>'.
                'Synergize Provide Service Co., Ltd.<br>'.
                '31/14 หมู่ 10 ต.ลาดสวาย อ.ลำลูกกา จ.ปทุมธานี 12150<br>'.
                '31/14 Moo 10, T.Ladsawai, A.Lamlukka, Pathumthani 12150<br>'.
                'Mobile phone: +668 7585 8635<br>'.
                'Tel  : +662 157 1325<br>'.
                'Fax : +662 157 1328<br>'.
                '<a href="url">www.synergize-th.com</a><br>'.
                '<a href="url">poowapong@synergize.co.th</a><br>';
                $res = smtp($subject, $body, $row['email']);
            }
            return $response->withStatus(200)->getBody()->write("SUCCESS");
            $db = null;

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    }
});

// Add Service Request
$app->post('/api/admin/addservice', function(Request $request, Response $response){
    //Prepare Required Parameters
    $title = $request->getParam('title');
    $description = $request->getParam('description');
    $work_class = $request->getParam('work_class');
    $contact_name = $request->getParam('contact_name');
    $contact_number = $request->getParam('contact_number');
    $alternate_number = $request->getParam('alternate_number');
    $asset = $request->getParam('asset');
    $fse_code = $request->getParam('fse_code');
    $leader = $request->getParam('leader');
    $due_date = $request->getParam('due_date');
    $status = $request->getParam('job_status');

    $result = add_service_request($title, $description, $work_class, $contact_name, $contact_number, $alternate_number, $asset, $fse_code, $leader, $due_date, $status);

    if (substr($result,0,3) == 'JOB') {
        return $response->withStatus(200)->getBody()->write("SUCCESS");
    }

});

// Update Service Request
$app->post('/api/admin/updateservice', function(Request $request, Response $response){
    //Prepare Required Parameters
    $service_request_id = $request->getParam('service_request_id');
    $title = $request->getParam('title');
    $description = $request->getParam('description');
    $work_class = $request->getParam('work_class');
    $contact_name = $request->getParam('contact_name');
    $contact_number = $request->getParam('contact_number');
    $alternate_number = $request->getParam('alternate_number');
    $asset = $request->getParam('asset');
    $fse_code = $request->getParam('fse_code');
    $leader = $request->getParam('leader');
    $due_date = $request->getParam('due_date');
    $status = $request->getParam('job_status');

    if($asset == ""){
        return 'error: no asset selected';
    }

    if($fse_code == ""){
        return 'error: no fse assigned';
    }

    //Check all Assets exist and have same location_code
    $location_temp = '';
    foreach ($asset as &$sng_code) {
        //Filter out empty asset field
        if ($sng_code != '') {
            $sql = "SELECT sng_code, location_code 
                    FROM asset_tracker 
                    WHERE sng_code = '$sng_code' 
                    LIMIT 1";
            try{
                $db = new db();
                $db = $db->connect();
                $stmt = $db->query($sql);
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                $result = json_encode($result);
                $result = json_decode($result, true);
                if (!$result) { return 'error: ' . $sng_code . ' not found'; }
                if($location_temp == '') {
                    $location_temp = $result['location_code'];
                } elseif ($location_temp != $result['location_code']) {
                    return 'error: all assets do not have same location_code';
                }
            } catch(PDOException $e) {
                $db = null;
                return $response->withStatus(400)->getBody()->write($e->getmessage());
            }
        }
    }

    //Insert new row to service_request table
    $sql = "UPDATE service_request SET
                service_request_id  = :set_service_request_id,
                title               = :set_title,
                description         = :set_description,
                status              = :set_status,
                contact_name        = :set_contact_name,
                contact_number      = :set_contact_number,
                alternate_number    = :set_alternate_number,
                work_class          = :set_work_class,
                due_date            = :set_due_date
            WHERE service_request_id = '$service_request_id'";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_service_request_id', $service_request_id);
        $stmt->bindParam(':set_title', $title);
        $stmt->bindParam(':set_description', $description);
        $stmt->bindParam(':set_status', $status);
        $stmt->bindParam(':set_contact_name', $contact_name);
        $stmt->bindParam(':set_contact_number', $contact_number);
        $stmt->bindParam(':set_alternate_number', $alternate_number);
        $stmt->bindParam(':set_work_class', $work_class);
        $stmt->bindParam(':set_due_date', $due_date);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    //Clear old service_asset row
    $sql = "DELETE FROM service_asset 
            WHERE service_request_id = '$service_request_id'";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());        
    }

    //Insert new row to service_asset table
    $sql = "INSERT INTO service_asset (service_request_id, sng_code) VALUES ";

    foreach ($asset as $value) {
        if ($value != '') {
            $sql = $sql . "('" . $service_request_id . "', '" . $value . "'),";
        }
    }
    $last_char = strlen($sql) - 1;
    $sql[$last_char] = ";";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    //Clear old service_fse row
    $sql = "DELETE FROM service_fse 
            WHERE service_request_id = '$service_request_id'";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    //Insert new row to service_fse table
    $sql = "INSERT INTO service_fse (service_request_id, fse_code, is_leader) VALUES ";

    foreach ($fse_code as $value) {
      if ($value == $leader) {
        $sql = $sql . "('" . $service_request_id . "', '" . $value . "', '" . 1 . "'),";
      } else {
        $sql = $sql . "('" . $service_request_id . "', '" . $value . "', '" . 0 . "'),";
      }
    }
    $last_char = strlen($sql) - 1;
    $sql[$last_char] = ";";
    try {
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    //Logging action
    $sql = "SELECT service_request_id 
            FROM service_request 
            WHERE service_request_id = '$service_request_id' 
            LIMIT 1";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        system_log('Update Service with Service ID: ' . $result['service_request_id']);
        $db = null;
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add Maintenance Plan
$app->post('/api/admin/addplan', function(Request $request, Response $response){

    //Prepare Required Parameters
    $title = $request->getParam('title');
    $description = $request->getParam('description');
    $work_class = $request->getParam('work_class');
    $contact_name = $request->getParam('contact_name');
    $contact_number = $request->getParam('contact_number');
    $alternate_number = $request->getParam('alternate_number');
    $asset = $request->getParam('asset');
    $fse_code = $request->getParam('fse_code');
    $leader = $request->getParam('leader');
    $status = $request->getParam('job_status');

    $start_date = $request->getParam('start_date');
    $year_count = $request->getParam('year_count');
    $times_per_year = $request->getParam('times_per_year');
    $plan_date = $request->getParam('plan_date');
    $sale_order_no = $request->getParam('sale_order_no');

    $location_code = $request->getParam('location_code');

    if($asset == ""){
        return 'error: no asset selected';
    }

    if($fse_code == ""){
        return 'error: no fse assigned';
    }

    //Check all Assets exist and have same location_code
    $location_temp = '';
    foreach ($asset as &$sng_code) {
      //Filter out empty asset field
      if ($sng_code != '') {
        $sql = "SELECT sng_code, location_code 
                FROM asset_tracker 
                WHERE sng_code = '$sng_code' 
                LIMIT 1";
        try{
          $db = new db();
          $db = $db->connect();
          $stmt = $db->query($sql);
          $result = $stmt->fetch(PDO::FETCH_OBJ);
          $result = json_encode($result);
          $result = json_decode($result, true);
          if (!$result) { return 'error: ' . $sng_code . ' not found'; }
          if($location_temp == '') {
            $location_temp = $result['location_code'];
          } elseif ($location_temp != $result['location_code']) {
            return 'error: all assets do not have same location_code';
          }
        } catch(PDOException $e) {
          $db = null;
          return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
      }
    }

    //Generate next maintenance_plan_id
    $maintenance_plan_id = $location_code . '-PM-';
    $sql = "SELECT MAX(maintenance_plan_id)
            FROM maintenance_plan
            WHERE maintenance_plan_id LIKE '$maintenance_plan_id%'
            LIMIT 1";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $result = json_encode($result);
        $result = json_decode($result, true);
        if ($result[0]['MAX(maintenance_plan_id)'] != '') {
            $current_number =  $result[0]['MAX(maintenance_plan_id)'];
            $current_number = substr($current_number,-4);
            $current_number = sprintf('%04d', $current_number + 1);
            $maintenance_plan_id = $maintenance_plan_id.$current_number;
        } else {
            $maintenance_plan_id = $maintenance_plan_id.'0001';
        }
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    //Insert new row to maintenance_plan table
    $sql = "INSERT INTO maintenance_plan (maintenance_plan_id, title, start_date, year_count, 
                        times_per_year, sale_order_no)
            VALUES (:set_maintenance_plan_id, :set_title, :set_start_date, :set_year_count, 
                    :set_times_per_year, :set_sale_order_no)";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_maintenance_plan_id', $maintenance_plan_id);
        $stmt->bindParam(':set_title', $title);
        $stmt->bindParam(':set_start_date', $start_date);
        $stmt->bindParam(':set_year_count', $year_count);
        $stmt->bindParam(':set_times_per_year', $times_per_year);
        $stmt->bindParam(':set_sale_order_no', $sale_order_no);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    //Batch add_service_request for each plan_date[]
    foreach ($plan_date as $index=>$due_date) {
        $service_request_id = add_service_request($title." ครั้งที่ ".(string)($index + 1), $description." ครั้งที่ ".(string)($index + 1), $work_class, $contact_name, $contact_number, $alternate_number, $asset, $fse_code, $leader, $due_date, $status);
        //Insert new row to maintenance_service table
        $sql = "INSERT INTO maintenance_service (maintenance_plan_id, service_request_id)
                VALUES (:set_maintenance_plan_id, :set_service_request_id)";
        try{
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':set_maintenance_plan_id', $maintenance_plan_id);
            $stmt->bindParam(':set_service_request_id', $service_request_id);
            $stmt->execute();
        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
    }

    //Logging action
    $sql = "SELECT maintenance_plan_id 
            FROM maintenance_plan 
            WHERE maintenance_plan_id = '$maintenance_plan_id' 
            LIMIT 1";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        system_log('Add Service with Maintenance Plan ID: ' . $result['maintenance_plan_id']);
        $db = null;
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    }catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

function add_service_request($title, $description, $work_class, $contact_name, $contact_number, $alternate_number, $asset, $fse_code, $leader, $due_date, $status) {

    //Generate next service_request_id
    $sql = "SELECT MAX(service_request_id) 
            FROM service_request";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $result = json_encode($result);
        $result = json_decode($result, true);
        $current_number =  $result[0]['MAX(service_request_id)'];
        if(substr($current_number,0,3)=='JOB'){
        	$current_number = substr($current_number,3);
        }
        $service_request_id = 'JOB' . sprintf('%06d', $current_number + 1);
    } catch(PDOException $e) {
        $db = null;
        echo '{"error": {"text": '.$e->getMessage().'}';
    }

    if($asset == ""){
        return 'error: no asset selected';
    }

    if($fse_code == ""){
        return 'error: no fse assigned';
    }

    //Check all Assets exist and have same location_code
    $location_temp = '';
    foreach ($asset as &$sng_code) {
      //Filter out empty asset field
      if ($sng_code != '') {
        $sql = "SELECT sng_code, location_code 
                FROM asset_tracker 
                WHERE sng_code = '$sng_code' 
                LIMIT 1";
        try{
          $db = new db();
          $db = $db->connect();
          $stmt = $db->query($sql);
          $result = $stmt->fetch(PDO::FETCH_OBJ);
          $result = json_encode($result);
          $result = json_decode($result, true);
          if (!$result) { return 'error: ' . $sng_code . ' not found'; }
          if($location_temp == '') {
            $location_temp = $result['location_code'];
          } elseif ($location_temp != $result['location_code']) {
            return 'error: all assets do not have same location_code';
          }
        } catch(PDOException $e) {
          $db = null;
          echo '{"error": {"text": '.$e->getMessage().'}';
        }
      }
    }

    //Insert new row to service_request table
    $sql = "INSERT INTO service_request (service_request_id, title, description, status, contact_name, 
                                        contact_number, alternate_number, work_class, due_date)
            VALUES (:set_service_request_id, :set_title, :set_description, :set_status, :set_contact_name, 
                    :set_contact_number, :set_alternate_number, :set_work_class, :set_due_date)";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_service_request_id', $service_request_id);
        $stmt->bindParam(':set_title', $title);
        $stmt->bindParam(':set_description', $description);
        $stmt->bindParam(':set_status', $status);
        $stmt->bindParam(':set_contact_name', $contact_name);
        $stmt->bindParam(':set_contact_number', $contact_number);
        $stmt->bindParam(':set_alternate_number', $alternate_number);
        $stmt->bindParam(':set_work_class', $work_class);
        $stmt->bindParam(':set_due_date', $due_date);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $e->getmessage();
    }

    //Insert new row to service_asset table
    $sql = "INSERT INTO service_asset (service_request_id, sng_code) VALUES ";

    foreach ($asset as $value) {
        if ($value != '') {
            $sql = $sql . "('" . $service_request_id . "', '" . $value . "'),";
        }
    }
    $last_char = strlen($sql) - 1;
    $sql[$last_char] = ";";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $e->getmessage();
    }

    //Insert new row to service_fse table
    $sql = "INSERT INTO service_fse (service_request_id, fse_code, is_leader) VALUES ";

    foreach ($fse_code as $value) {
      if ($value == $leader) {
        $sql = $sql . "('" . $service_request_id . "', '" . $value . "', '" . 1 . "'),";
      } else {
        $sql = $sql . "('" . $service_request_id . "', '" . $value . "', '" . 0 . "'),";
      }
    }
    $last_char = strlen($sql) - 1;
    $sql[$last_char] = ";";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $e->getmessage();
    }

    //Logging action
    $sql = "SELECT service_request_id 
            FROM service_request 
            WHERE service_request_id = '$service_request_id' 
            LIMIT 1";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $result = json_encode($result);
        $result = json_decode($result, true);
        system_log('Add Service with Service ID: ' . $result['service_request_id']);
        $db = null;
        return $service_request_id;

    }catch(PDOException $e){
        $db = null;
        return $e->getmessage();
    }
}

// Edit customer asset
$app->put('/api/admin/updateasset', function(Request $request, Response $response){
    $sng_code = $request->getParam('sng_code');
    $customer_no = $request->getParam('customer_no');
    $location_code = $request->getParam('location_code');
    $sale_order_no = $request->getParam('sale_order_no');
    $contactname = $request->getParam('contactname');
    $contactnumber = $request->getParam('contactnumber');
    $pmyear = $request->getParam('pmyear');
    $nextpm = $request->getParam('nextpm');
    $itemnumber = $request->getParam('itemnumber');
    $serial = $request->getParam('serial');
    $fse_code = $request->getParam('fse_code');
    $battery = $request->getParam('battery');
    $quantity = $request->getParam('quantity');
    $battery_date = $request->getParam('battery_date');
    $startwarranty = $request->getParam('startwarranty');
    $endwarranty = $request->getParam('endwarranty');
    $ups_status = $request->getParam('ups_status');
    $sla_conditon = $request->getParam('sla_condition');
    $sla_response = $request->getParam('sla_response');
    $sla_recovery = $request->getParam('sla_recovery');
    $typeofcontract = $request->getParam('toc');


    $sql = "UPDATE asset_tracker SET
                customer_no     = :set_customer_no,
                location_code   = :set_location_code,
                sale_order_no   = :set_sale_order_no,
                contactname     = :set_contactname,
                contactnumber   = :set_contactnumber,
                pmyear          = :set_pmyear,
                nextpm          = :set_nextpm,
                itemnumber      = :set_itemnumber,
                serial          = :set_serial,
                fse_code        = :set_fse_code,
                battery         = :set_battery,
                quantity        = :set_quantity,
                battery_date    = :set_battery_date,
                startwarranty   = :set_startwarranty,
                endwarranty     = :set_endwarranty,
                ups_status      = :set_ups_status,
                sla_condition   = :set_sla_condition,
                sla_response    = :set_sla_response,
                sla_recovery    = :set_sla_recovery,
                typeofcontract  = :set_typeofcontract
            WHERE asset_tracker.sng_code = '$sng_code'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_customer_no', $customer_no);
        $stmt->bindParam(':set_location_code', $location_code);
        $stmt->bindParam(':set_sale_order_no', $sale_order_no);
        $stmt->bindParam(':set_contactname', $contactname);
        $stmt->bindParam(':set_contactnumber', $contactnumber);
        $stmt->bindParam(':set_pmyear', $pmyear);
        $stmt->bindParam(':set_nextpm', $nextpm);
        $stmt->bindParam(':set_itemnumber', $itemnumber);
        $stmt->bindParam(':set_serial', $serial);
        $stmt->bindParam(':set_fse_code', $fse_code);
        $stmt->bindParam(':set_battery', $battery);
        $stmt->bindParam(':set_quantity', $quantity);
        $stmt->bindParam(':set_battery_date', $battery_date);
        $stmt->bindParam(':set_startwarranty', $startwarranty);
        $stmt->bindParam(':set_endwarranty', $endwarranty);
        $stmt->bindParam(':set_ups_status', $ups_status);
        $stmt->bindParam(':set_sla_condition', $sla_conditon);
        $stmt->bindParam(':set_sla_response', $sla_response);
        $stmt->bindParam(':set_sla_recovery', $sla_recovery);
        $stmt->bindParam(':set_typeofcontract', $typeofcontract);



        $stmt->execute();
        system_log('Edit asset at SNGCODE: ' . $sng_code);
        $db = null;
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get single asset
$app->get('/api/admin/singleasset', function(Request $request, Response $response){
    $sng_code = $request->getParam('sng_code');

    $sql = "SELECT sng_code, asset_tracker.sale_order_no, sale_order.since, contactname, contactnumber, 
                    pmyear, nextpm, typeofcontract, sla_condition, sla_response, sla_recovery, date_order, 
                    po_number, po_date, customer_name, model, serial, engname, do_number, battery, quantity, 
                    battery_date, sitename, house_no, village_no, soi, road, sub_district, district, province, 
                    postal_code, region, country, contactname, contactnumber, startwarranty,
                    endwarranty, ups_status, pmyear, nextpm, power, asset_tracker.itemnumber, date_order,
                    po_number, do_number, asset_tracker.location_code, asset_tracker.customer_no
            FROM asset_tracker, location, sale_order, customers, fse, material_master_record 
            WHERE asset_tracker.sng_code = '$sng_code'
                AND asset_tracker.location_code = location.location_code
                AND sale_order.sale_order_no = asset_tracker.sale_order_no
                AND asset_tracker.customer_no = customers.customer_no 
                AND asset_tracker.fse_code = fse.fse_code
                AND asset_tracker.itemnumber = material_master_record.itemnumber";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get single customer request
$app->get('/api/admin/request/single', function(Request $request, Response $response){
    $cm_id = $request->getParam('cm_id');
    $sql = "SELECT sng_code, name, email, phone_number, asset_problem, asset_detected,
                    srm_request.cause_id, cause_description, srm_request.correction_id, correction_description,
                    CONCAT('{', GROUP_CONCAT(CONCAT('\"', fse_code, '\"', ':', is_leader)), '}') AS groupFSE, cm_time, complete_time, job_status, correction_detail, cause_detail,
                    problem_type, solution, suggestions, job_type, start_time, close_time
            FROM srm_request, root_cause, correction, job_fse
            WHERE cm_id = '$cm_id' 
                AND srm_request.cause_id = root_cause.cause_id
                AND srm_request.correction_id = correction.correction_id
                AND job_fse.job_id = srm_request.cm_id 
            GROUP BY srm_request.cm_id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get single customer request
$app->get('/api/admin/workload', function(Request $request, Response $response){
    $fse_code = $request->getParam('fse_code');
    $month = $request->getParam('month');
    $year = $request->getParam('year');

    $sql = "SELECT * FROM (SELECT sub_q2.abbr, sub_q2.date_start,  sub_q2.cm_id, sub_q2.location_code, sub_q2.customer_name, sub_q2.sitename, sub_q2.work_class,
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
                      AND MONTH(sub_q3.date_start) = '$month'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all customer request
$app->get('/api/admin/request', function(Request $request, Response $response){
     $sql = "SELECT cm_id sng_code, name, email, phone_number, asset_problem, asset_detected,
                cause_description, correction_description, fse, cm_time, close_time, job_status 
            FROM srm_request, root_cause
            WHERE srm_request.cause_id = root_cause.cause_id
                AND srm_request.correction_id = correction.correction_id";
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            $db = null;
            return $response->withJson($result);

        } catch(PDOException $e) {
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
});

// Get asset location code
$app->get('/api/admin/get_asset_location_code', function(Request $request, Response $response){
    $sng_code = $request->getParam('sng_code');
    $sql = "SELECT location.sitename, asset_tracker.location_code
            FROM asset_tracker, location
            WHERE asset_tracker.sng_code = '$sng_code'
                AND asset_tracker.location_code = location.location_code";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    return $response->withJson($result);
});

// Get asset location and customer
$app->get('/api/admin/get_asset_location_and_customer', function(Request $request, Response $response){
    $sng_code = $request->getParam('sng_code');
    $sql = "SELECT location.sitename, asset_tracker.location_code, customers.customer_name, customers.customer_no
            FROM asset_tracker, location, customers
            WHERE asset_tracker.sng_code = '$sng_code'
                AND asset_tracker.location_code = location.location_code
                AND location.customer_no = customers.customer_no";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    return $response->withJson($result);
});

// Get lastest asset warranty
$app->get('/api/admin/get_asset_warranty', function(Request $request, Response $response){
    $sng_code = $request->getParam('sng_code');
    $sql = "SELECT start_date, end_date, year_count, times_per_year, typeofcontract
            FROM asset_tracker, warranty
            WHERE warranty.sng_code = '$sng_code'
                AND warranty.sng_code = asset_tracker.sng_code";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    return $response->withJson($result);
});

// Get single service request
$app->get('/api/admin/service/single', function(Request $request, Response $response){
    $service_request_id = $request->getParam('service_request_id');

    //Get service_request with fse
    $sql = "SELECT service_request.service_request_id, title, description, work_class, contact_name,
                contact_number, alternate_number, GROUP_CONCAT(fse_code), GROUP_CONCAT(is_leader), 
                due_date, status AS job_status
            FROM service_request, service_fse
            WHERE service_request.service_request_id = '$service_request_id'
                AND service_request.service_request_id = service_fse.service_request_id
            GROUP BY service_request.service_request_id";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $request1 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    //Get service_request assets
    $sql = "SELECT GROUP_CONCAT(sng_code)
            FROM service_asset
            WHERE service_request_id = '$service_request_id'
            GROUP BY service_request_id";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $request2 = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $json_data = array_merge($request1, $request2);

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
    return $response->withJson($json_data);
});

// Get single maintenance plan
$app->get('/api/admin/plan/single', function(Request $request, Response $response){
    $maintenance_plan_id = $request->getParam('maintenance_plan_id');

    //Get service_request with fse
    $sql = "SELECT maintenance_plan_id, title, start_date, year_count, times_per_year
            FROM maintenance_plan
            WHERE maintenance_plan_id = '$maintenance_plan_id'
            LIMIT 1";
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all user
$app->get('/api/admin/getuser', function(Request $request, Response $response){
    $sql = "SELECT account_status, account_type, is_lock, last_login, account_name, username_tag 
            FROM account
            LEFT JOIN customers 
                ON customers.customer_no = account.account_no";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            return $response->withJson($result);

        } catch(PDOException $e){
            $db = null;
            return $response->withStatus(400)->getBody()->write($e->getmessage());
        }
});


// Get FSE for assigning FSE.
$app->get('/api/admin/getfse', function (Request $request, Response $response) {
    $sql = "SELECT engname, fse_code, status 
            FROM fse 
            ORDER BY engname ASC";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get specified FSE information for FSE modal.
$app->get('/api/admin/getsinglefse', function (Request $request, Response $response) {
    $username = $request->getParam('username');
    $sql = "SELECT fse_code FROM fse WHERE username = '$username'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all latitude and longitude for work location on google map. 
$app->get('/api/admin/getcminfo', function (Request $request, Response $response) {
    $cm_id = $request->getParam('cm_id');
    $sql = "SELECT cm_time, 
                    CONCAT('{', GROUP_CONCAT(DISTINCT CONCAT('\"', fse.fse_code, '\"', ':', '\"', fse.engname, '\"') ORDER BY fse.engname), '}') AS groupFSE,
                    job_type, sitename, asset_tracker.sng_code
                  FROM srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse
                  WHERE asset_tracker.sng_code    = srm_request.sng_code
                    AND location.location_code    = asset_tracker.location_code
                    AND asset_tracker.itemnumber  = material_master_record.itemnumber
                    AND srm_request.cm_id         = '$cm_id'
                    AND job_fse.job_id            = '$cm_id'
                    AND fse.fse_code              = job_fse.fse_code
                    AND srm_request.cause_id      = root_cause.cause_id
                    AND srm_request.correction_id = correction.correction_id
                    AND srm_request.job_status    != 'Closed'
                    AND srm_request.job_status    != 'Completed'
                GROUP BY srm_request.cm_id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e) { 
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all latitude and longitude for work location on google map. 
$app->get('/api/admin/getlatlon', function (Request $request, Response $response) {
    $sql = "SELECT location.location_code, latitude, longitude, sitename, GROUP_CONCAT(DISTINCT cm_id ORDER BY cm_id) AS groupCM
                  FROM srm_request, asset_tracker, location, material_master_record, fse, root_cause, correction, job_fse
                  WHERE asset_tracker.sng_code      = srm_request.sng_code
                    AND location.location_code    = asset_tracker.location_code
                    AND asset_tracker.itemnumber  = material_master_record.itemnumber
                    AND srm_request.cm_id         = job_fse.job_id
                    AND fse.fse_code              = job_fse.fse_code
                    AND srm_request.cause_id      = root_cause.cause_id
                    AND srm_request.correction_id = correction.correction_id
                    AND srm_request.job_status    != 'Closed'
                    AND srm_request.job_status    != 'Completed'
                    AND srm_request.job_status    != 'Incomplete'
                  GROUP BY asset_tracker.location_code";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e) { 
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all pasasword recovery message from customers
$app->get('/admin/recover', function (Request $request, Response $response) {
    $sql = "SELECT * FROM recover_msg";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Reset password
$app->put('/admin/recover/reset', function (Request $request, Response $response) {
    $username = $request->getParam('username');
    $new_password = $request->getParam('new_password');
    $email = $request->getParam('email');
    $hash_password = hash('sha256', $new_password);
    $user_msg = $new_password;

    if (!check_pass($new_password)) return '0';

    $sql = "UPDATE account SET password = :set_password 
            WHERE username_tag = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_password', $hash_password);
        $stmt->execute();
        $db = null;
        
    } catch(PDOException $e) { 
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    $sql = "UPDATE recover_msg SET status = :set_status 
            WHERE username = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $status = 'READ';
        $stmt->bindParam(':set_status', $status);
        $stmt->execute();
        $subject = 'Syngergize SRM';
        $body = '<b>Dear Synergize SRM Client,</b><br>'.
        '&nbsp;&nbsp;We received your request to recover SRM account<br>'.
        '&nbsp;&nbsp;Username: ' . $username . '<br>'.
        '&nbsp;&nbsp;Password: ' . $new_password . '<br>'.
        '------------------------------------------------------------------------------------------------------<br>'.
        'If you have any question, please do not hesitate to contact us.<br><br>'.
        '<b>Best regards,</b><br>'.
        'บริษัท ซินเนอร์ไจซ์ โปรไวด์ เซอร์วิส จำกัด<br>'.
        'Synergize Provide Service Co., Ltd.<br>'.
        '31/14 หมู่ 10 ต.ลาดสวาย อ.ลำลูกกา จ.ปทุมธานี 12150<br>'.
        '31/14 Moo 10, T.Ladsawai, A.Lamlukka, Pathumthani 12150<br>'.
        'Mobile phone: +668 7585 8635<br>'.
        'Tel  : +662 157 1325<br>'.
        'Fax : +662 157 1328<br>'.
        '<a href="url">www.synergize-th.com</a><br>'.
        '<a href="url">poowapong@synergize.co.th</a>';
        // Send email to customer
        $res = smtp($subject, $body, $email);
        system_log('Reset password for user: '. $username);
        $db = null;
        return $response->withStatus(200)->getBody()->write($res);

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

});

$app->put('/api/customer/resetpassword', function (Request $request, Response $response) {
    $old_password = hash('sha256', $request->getParam('old_password'));
    $username_tag = $_SESSION['username_unhash'];
    $new_password = $request->getParam('new_password');
    $confirm_password = $request->getParam('confirm_new_password');
    $hash_password = hash('sha256', $new_password);

    $sql = "SELECT * FROM account 
            WHERE username_tag = '$username_tag' 
                AND password = '$old_password'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        if(!$result) return '1';

        $db = null;
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }

    $sql = "UPDATE account SET password = :set_password 
            WHERE username_tag = '$username_tag'";

    try{

        if ($new_password != $confirm_password) return 0;
        if (!check_pass($confirm_password)) return '2';

        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_password', $hash_password);
        $stmt->execute();
        $db = null;
        system_log('Reset password for user: '. $username_tag);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Update status account
$app->put('/api/admin/account', function(Request $request, Response $response){
    $username = $request->getParam('username');

    $sql = "UPDATE account SET is_lock = Not is_lock 
            WHERE username_tag = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        system_log('Set Lock status for user: '. $username );
        $db = null;
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});



// Update location information
$app->put('/api/admin/updatelocation', function(Request $request, Response $response){
    $location_code = $request->getParam('location_code');
    $sitename = $request->getParam('sitename');
    $house_no = $request->getParam('house_no');
    $village_no = $request->getParam('village_no');
    $road = $request->getParam('road');
    $district = $request->getParam('district');
    $sub_district = $request->getParam('sub_district');
    $province = $request->getParam('province');
    $postal_code = $request->getParam('postal_code');
    $region = $request->getParam('region');
    $country = $request->getParam('country');
    $store_phone = $request->getParam('store_phone');
    $lat = $request->getParam('lat');
    $lon = $request->getParam('lon');
    $soi = $request->getParam('soi');

    $sql = "UPDATE location SET
                sitename      = :set_sitename,
                house_no      = :set_house_no,
                village_no    = :set_village_no,
                road          = :set_road,
                district      = :set_district,
                sub_district  = :set_sub_district,
                province      = :set_province,
                postal_code   = :set_postal_code,
                region        = :set_region,
                country       = :set_country,
                store_phone   = :set_store_phone,
                latitude      = :set_latitude,
                longitude     = :set_longitude,
                soi           = :set_soi
            WHERE location_code = '$location_code'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_sitename', $sitename);
        $stmt->bindParam(':set_house_no', $house_no);
        $stmt->bindParam(':set_village_no', $village_no);
        $stmt->bindParam(':set_road', $road);
        $stmt->bindParam(':set_district', $district);
        $stmt->bindParam(':set_sub_district', $sub_district);
        $stmt->bindParam(':set_province', $province);
        $stmt->bindParam(':set_postal_code', $postal_code);
        $stmt->bindParam(':set_region', $region);
        $stmt->bindParam(':set_country', $country);
        $stmt->bindParam(':set_store_phone', $store_phone);
        $stmt->bindParam(':set_latitude', $lat);
        $stmt->bindParam(':set_longitude', $lon);
        $stmt->bindParam(':set_soi', $soi);

        $stmt->execute();

        $db = null;
        system_log('Update location informmation: '. $location_code);
        return $response->withStatus(200)->getBody()->write("SUCCESS");
    } catch(PDOException $e) { 
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Update item information
$app->put('/api/admin/updateitem', function(Request $request, Response $response){
    $itemnumber = $request->getParam('itemnumber');
    $model = $request->getParam('model');
    $item_class = $request->getParam('item_class');
    $category = $request->getParam('category');
    $is_lot = $request->getParam('is_lot');
    $is_serial = $request->getParam('is_serial');
    $is_warranty = $request->getParam('is_warranty');
    $power = $request->getParam('power');

    $sql = "UPDATE material_master_record SET
                model           = :set_model,
                item_class      = :set_item_class,
                category        = :set_category,
                is_lot          = :set_is_lot,
                is_serial       = :set_is_serial,
                is_warranty     = :set_is_warranty,
                power           = :set_power
            WHERE itemnumber = '$itemnumber'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_model', $model);
        $stmt->bindParam(':set_item_class', $item_class);
        $stmt->bindParam(':set_category', $category);
        $stmt->bindParam(':set_is_lot', $is_lot);
        $stmt->bindParam(':set_is_serial', $is_serial);
        $stmt->bindParam(':set_is_warranty', $is_warranty);
        $stmt->bindParam(':set_power', $power);

        $stmt->execute();

        $db = null;
        system_log('Update item informmation: '. $itemnumber);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Update customer information
$app->put('/api/admin/updatecustomer', function(Request $request, Response $response){
    $customer_no = $request->getParam('customer_no');
    $customer_name = $request->getParam('customer_name');
    $customer_eng_name = $request->getParam('customer_eng_name');
    $sale_team = $request->getParam('sale_team');
    $account_group = $request->getParam('account_group');
    $primary_contact = $request->getParam('primary_contact');
    $product_sale = $request->getParam('product_sale');
    $service_sale = $request->getParam('service_sale');
    $taxid = $request->getParam('taxid');

    $sql = "UPDATE customers SET
                customer_name      = :set_customer_name,
                customer_eng_name  = :set_customer_eng_name,
                sale_team          = :set_sale_team,
                account_group      = :set_account_group,
                primary_contact    = :set_primary_contact,
                product_sale      = :set_product_sale,
                service_sale      = :set_service_sale,
                taxid              = :set_taxid
            WHERE customer_no = '$customer_no'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_customer_name', $customer_name);
        $stmt->bindParam(':set_customer_eng_name', $customer_eng_name);
        $stmt->bindParam(':set_sale_team', $sale_team);
        $stmt->bindParam(':set_account_group', $account_group);
        $stmt->bindParam(':set_primary_contact', $primary_contact);
        $stmt->bindParam(':set_product_sale', $product_sale);
        $stmt->bindParam(':set_service_sale', $service_sale);
        $stmt->bindParam(':set_taxid', $taxid);


        $stmt->execute();

        $db = null;
        system_log('Update customer informmation: '. $customer_name);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Update sale order information
$app->put('/api/admin/updatesaleorder', function(Request $request, Response $response){
    $sale_order_no = $request->getParam('sale_order_no');
    $date_order = $request->getParam('date_order');
    $since = $request->getParam('since');
    $po_number = $request->getParam('po_number');
    $po_date= $request->getParam('po_date');
    $do_number = $request->getParam('do_number');

    $sql = "UPDATE sale_order SET
                date_order   = :set_date_order,
                since        = :set_since,
                po_number    = :set_po_number,
                po_date      = :set_po_date,
                do_number    = :set_do_number
            WHERE sale_order_no = '$sale_order_no'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_date_order', $date_order);
        $stmt->bindParam(':set_since', $since);
        $stmt->bindParam(':set_po_number', $po_number);
        $stmt->bindParam(':set_po_date', $po_date);
        $stmt->bindParam(':set_do_number', $do_number);

        $stmt->execute();

        $db = null;
        system_log('Update sale order informmation: '. $sale_order_no);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
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
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add srm user
$app->post('/api/adduser', function(Request $request, Response $response){
    $acc_no = $request->getParam('account_no');
    $username_tag = $request->getParam('username');
    $username = hash('sha256', $username_tag);
    $unhash_password = $request->getParam('password');
    $password = hash('sha256', $unhash_password);
    $account_type = $request->getParam('account_type');
    $status = 'LOGOUT';
    $attempt = 0;
    $is_lock = false;

    $sql = "INSERT INTO account (account_no, username, password, account_status, attempt, is_lock, 
                        account_type, username_tag) 
            VALUES (:set_account, :set_user, :set_pass, :set_status, :set_attempt, 
                    :set_lock, :set_type, :set_tag)";
    
    // return $unhash_password;
    if (!check_pass($unhash_password)) return '0';

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_account', $acc_no);
        $stmt->bindParam(':set_user', $username);
        $stmt->bindParam(':set_pass', $password);
        $stmt->bindParam(':set_status', $status);
        $stmt->bindParam(':set_attempt', $attempt);
        $stmt->bindParam(':set_lock', $is_lock);
        $stmt->bindParam(':set_type', $account_type);
        $stmt->bindParam(':set_tag', $username_tag);

        $stmt->execute();
        $db = null;
        // system_log('Add user for user: '. $username_tag);
        return $response->withStatus(200)->getBody()->write("SUCCESS");
    
    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add customer
$app->post('/api/admin/addcustomer', function(Request $request, Response $response){
    $customer_no = $request->getParam('customer_no');
    $customer_name = $request->getParam('customer_name');
    $customer_eng_name = $request->getParam('customer_eng_name');
    $sale_team = $request->getParam('sale_team');
    $account_group = $request->getParam('account_group');
    $primary_contact = $request->getParam('primary_contact');
    $product_sale = $request->getParam('product_sale');
    $service_sale = $request->getParam('service_sale');
    $taxid = $request->getParam('taxid');
    
    $sql = "INSERT INTO customers (customer_no, customer_name, customer_eng_name, sale_team, account_group, 
                        primary_contact, product_sale, service_sale, taxid )
            VALUES (:set_customer_no, :set_customer_name, :set_customer_eng_name, :set_sale_team, 
                    :set_account_group, :set_primary_contact, :set_product_sale, :set_service_sale, :set_taxid)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_customer_no', $customer_no);
        $stmt->bindParam(':set_customer_name', $customer_name);
        $stmt->bindParam(':set_customer_eng_name', $customer_eng_name);
        $stmt->bindParam(':set_sale_team', $sale_team);
        $stmt->bindParam(':set_account_group', $account_group);
        $stmt->bindParam(':set_primary_contact', $primary_contact);
        $stmt->bindParam(':set_product_sale', $product_sale);
        $stmt->bindParam(':set_service_sale', $service_sale);
        $stmt->bindParam(':set_taxid', $taxid);

        $stmt->execute();
        $db = null;
        system_log('Add customer: '. $customer_name);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add location
$app->post('/api/admin/addlocation', function(Request $request, Response $response){
    $location_code = $request->getParam('location_code');
    $sitename = $request->getParam('sitename');
    $customer_no = $request->getParam('customer_no');
    $house_no = $request->getParam('house_no');
    $village_no = $request->getParam('village_no');
    $road = $request->getParam('road');
    $district = $request->getParam('district');
    $sub_district = $request->getParam('sub_district');
    $province = $request->getParam('province');
    $postal_code = $request->getParam('postal_code');
    $region = $request->getParam('region');
    $country = $request->getParam('country');
    $store_phone = $request->getParam('store_phone');
    $lat = $request->getParam('lat');
    $lon = $request->getParam('lon');
    $soi = $request->getParam('soi');

    $sql = "INSERT INTO location (location_code, sitename, house_no, village_no, road, district,
                                sub_district, province, postal_code, region, country, store_phone, 
                                customer_no, latitude, longitude, soi)
            VALUES (:set_location_code, :set_sitename, :set_house_no, :set_village_no, :set_road,
                    :set_district, :set_sub_district, :set_province, :set_postal_code, :set_region, :set_country, :set_store_phone, :set_customer_no,
                    :set_latitude, :set_longitude, :set_soi)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_location_code', $location_code);
        $stmt->bindParam(':set_sitename', $sitename);
        $stmt->bindParam(':set_house_no', $house_no);
        $stmt->bindParam(':set_village_no', $village_no);
        $stmt->bindParam(':set_road', $road);
        $stmt->bindParam(':set_district', $district);
        $stmt->bindParam(':set_sub_district', $sub_district);
        $stmt->bindParam(':set_province', $province);
        $stmt->bindParam(':set_postal_code', $postal_code);
        $stmt->bindParam(':set_region', $region);
        $stmt->bindParam(':set_country', $country);
        $stmt->bindParam(':set_store_phone', $store_phone);
        $stmt->bindParam(':set_customer_no', $customer_no);
        $stmt->bindParam(':set_latitude', $lat);
        $stmt->bindParam(':set_longitude', $lon);
        $stmt->bindParam(':set_soi', $soi);

        $stmt->execute();
        $db = null;
        system_log('Add location: '. $sitename);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add sale order
$app->post('/api/admin/addsaleorder', function(Request $request, Response $response){
    $sale_order_no = $request->getParam('sale_order_no');
    $date_order = $request->getParam('date_order');
    $since = $request->getParam('since');
    $po_number = $request->getParam('po_number');
    $po_date = $request->getParam('po_date');
    $do_number = $request->getParam('do_number');

    $sql = "INSERT INTO sale_order (sale_order_no, date_order, since, po_number, po_date, do_number)
            VALUES (:set_sale_order_no, :set_date_order, :set_since, :set_po_number, :set_po_date, :set_do_number)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_sale_order_no', $sale_order_no);
        $stmt->bindParam(':set_date_order', $date_order);
        $stmt->bindParam(':set_since', $since);
        $stmt->bindParam(':set_po_number', $po_number);
        $stmt->bindParam(':set_po_date', $po_date);
        $stmt->bindParam(':set_do_number', $do_number);

        $stmt->execute();
        $db = null;
        system_log('Add sale order: '. $sale_order_no);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add item
$app->post('/api/admin/additem', function(Request $request, Response $response){
    $itemnumber = $request->getParam('itemnumber');
    $item_class = $request->getParam('item_class');
    $category = $request->getParam('category');
    $is_lot = $request->getParam('is_lot');
    $is_serial = $request->getParam('is_serial');
    $is_warranty = $request->getParam('is_warranty');
    $power = $request->getParam('power');
    $model = $request->getParam('model');
    $created_on = date('Y-m-d H:i:s', time());

    $sql = "INSERT INTO material_master_record (itemnumber, model, item_class, category, is_lot, 
                        is_serial, is_warranty, power, created_on)
            VALUES (:set_itemnumber, :set_model, :set_item_class, :set_category, :set_is_lot, :set_is_serial, 
                    :set_is_warranty, :set_power, :set_created_on)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_itemnumber', $itemnumber);
        $stmt->bindParam(':set_model', $model);
        $stmt->bindParam(':set_item_class', $item_class);
        $stmt->bindParam(':set_category', $category);
        $stmt->bindParam(':set_is_lot', $is_lot);
        $stmt->bindParam(':set_is_serial', $is_serial);
        $stmt->bindParam(':set_is_warranty', $is_warranty);
        $stmt->bindParam(':set_power', $power);
        $stmt->bindParam(':set_created_on', $created_on);

        $stmt->execute();
        $db = null;
        system_log('Add item: '. $itemnumber);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Add fse
$app->post('/api/admin/addfse', function(Request $request, Response $response){
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
    $username = explode(" ", $engname)[0] . $abbr;

    $acc_no = '000000';
    $username_hash = hash('sha256', $username);
    $unhash_password = 'aA12345678';
    $password = hash('sha256', $unhash_password);
    $account_type = 'FSE';
    $acc_status = 'LOGOUT';
    $attempt = 0;
    $is_lock = false;

    $sql = "INSERT INTO account (account_no, username, password, account_status, attempt, is_lock,
                        account_type, username_tag)
            VALUES (:set_account, :set_user, :set_pass, :set_status, :set_attempt, :set_lock,
                    :set_type, :set_tag);
            INSERT INTO fse (fse_code, thainame, engname, abbr, company, position,
                            service_center, section, team, status, email, phone, username)
            VALUES (:set_fse_code, :set_thainame, :set_engname, :set_abbr, :set_company,
                    :set_position, :set_service_center, :set_section, :set_team, :set_status,
                    :set_email, :set_phone, :set_username);";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_fse_code', $fse_code);
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
        $stmt->bindParam(':set_username', $username);
        $stmt->bindParam(':set_account', $acc_no);
        $stmt->bindParam(':set_user', $username_hash);
        $stmt->bindParam(':set_pass', $password);
        $stmt->bindParam(':set_status', $acc_status);
        $stmt->bindParam(':set_attempt', $attempt);
        $stmt->bindParam(':set_lock', $is_lock);
        $stmt->bindParam(':set_type', $account_type);
        $stmt->bindParam(':set_tag', $username);

        $stmt->execute();
        $db = null;
        system_log('Add FSE: '. $engname);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all root cause
$app->get('/api/admin/getallcauses', function (Request $request, Response $response) {
    $sql = "SELECT cause_id, cause_description 
            FROM root_cause 
            ORDER BY cause_description ASC";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e){
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Get all correction
$app->get('/api/admin/getallcorrections', function (Request $request, Response $response) {
    $sql = "SELECT correction_id, correction_description 
            FROM correction 
            ORDER BY correction_description ASC";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($result);

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});

// Delete account
$app->delete('/api/admin/delete', function(Request $request, Response $response){
    $username = $request->getParam('username');

    $sql = "DELETE FROM account 
            WHERE username_tag = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        system_log('Delete: ' . $username);
        return $response->withStatus(200)->getBody()->write("SUCCESS");

    } catch(PDOException $e) {
        $db = null;
        return $response->withStatus(400)->getBody()->write($e->getmessage());
    }
});



function check_pass($password) {
    $upper = range('A', 'Z');
    $lower = range('a', 'z');
    $number = range('0', '9');

    $score = 0;

    if (strlen($password) >= 8){
        $score = $score +1;
    }

    for ($i = 0; $i < sizeof($upper); $i++){
        if (strpos($password, $upper[$i]) === FALSE){
            continue;
        }else{
            $score = $score + 1;
            break;
        }
    }

    for( $j = 0; $j < sizeof($lower); $j++){
        if (strpos($password, $lower[$j]) === FALSE){
            continue;
        }else{
            $score = $score + 1;
            break;
        }
    }

    for( $k = 0; $k < sizeof($number); $k++){
        if (strpos($password, (string)$number[$k]) === FALSE){
            continue;
        }else{
            $score = $score + 1;
            break;
        }
    }

    if ($score == 4) return TRUE;
    else return FALSE;
}


function system_log($action) {
    $sql = "INSERT INTO system_log (account_no, user, level, action, date_time) VALUES (:set_account_no, :set_user, :set_level, :set_action, :set_date_time)";
    $user =  $_SESSION['username_unhash'];
    $level = $_SESSION['account_type'];
    $account_no = $_SESSION['account_no'];
    $date_time = date('Y-m-d H:i:s', time());

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_account_no', $account_no);
        $stmt->bindParam(':set_user', $user);
        $stmt->bindParam(':set_level', $level);
        $stmt->bindParam(':set_action', $action);
        $stmt->bindParam(':set_date_time', $date_time);

        $stmt->execute();
        $db = null;
        return "SUCCESS";
    } catch(PDOException $e){
        $db = null;
        echo $e->getMessage();
    }
}

function system_log_mail($account_no, $level, $action, $username) {
    $sql = "INSERT INTO system_log (account_no, level, action, date_time, user)
            VALUES (:set_account_no, :set_level, :set_action, :set_date_time, :set_user)";

    $date_time = date('Y-m-d H:i:s', time());

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_account_no', $account_no);
        $stmt->bindParam(':set_level', $level);
        $stmt->bindParam(':set_action', $action);
        $stmt->bindParam(':set_date_time', $date_time);
        $stmt->bindParam(':set_user', $username);

        $stmt->execute();
        $db = null;
        return "SUCCESS";
    } catch(PDOException $e){
        $db = null;
        echo $e->getMessage();
    }
}


function smtp($subject, $body, $email) {
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'synergize.provider@gmail.com';     // SMTP username
        $mail->Password = '27042537poO';                      // SMTP password
        $mail->SMTPSecure = 'TLS';                           // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('synergize.provider@gmail.com', 'Synergize Service');
        $mail->addAddress($email);            // Name is optional
        $mail->addReplyTo('synergize.provider@gmail.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return 'SUCCESS';
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
    }
}

function firebase_connect() {
    // This assumes that you have placed the Firebase credentials in the same directory
    // as this PHP file.
    $serviceAccount = ServiceAccount::fromJsonFile('./srm-tracking-system-firebase-adminsdk-6acpo-f3ed3bbfb3.json');

    $firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    // The following line is optional if the project id in your credentials file
    // is identical to the subdomain of your Firebase project. If you need it,
    // make sure to replace the URL with the URL of your project.
    ->withDatabaseUri('https://srm-tracking-system.firebaseio.com/')
    ->create();

    return $firebase->getDatabase();
}

function get_fse_location($fse_code){
    $database = firebase_connect();
    $newPost = $database
    ->getReference('locations')->getChild($fse_code);

    return json_encode($newPost->getValue());
}

function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('SRMSNG');
    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
    $client->setAuthConfig('client_secret_28002227602-qoik01jcou25hhrb2o94ooelaq1qn9qq.apps.googleusercontent.com.json');
    $client->setAccessType('offline');
    
    // Load previously authorized credentials from a file.
    $credentialsPath = 'token.json';
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        return $authUrl;
        $authCode = "hMAd1GMpLd_IgG9ij4HCJccd";

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

function getEvent(){
    // Get the API client and construct the service object.
    $client = getClient();

    $service = new Google_Service_Calendar($client);

    // Get the next event on the user's calendar.
    $calendarId = 'th.th#holiday@group.v.calendar.google.com';
    $optParams = array(
    'maxResults' => 1,
    'orderBy' => 'startTime',
    'singleEvents' => true,
    'timeMin' => date('Y-m-d H:i:s', time()),
    );
    $results = $service->events->listEvents($calendarId, $optParams);
    $events = $results->getItems();

    if (empty($events)) {
        return 0;
    } else {
        foreach ($events as $event) {
            $start = $event->start->dateTime;
            return json_encode($start);
            // if (empty($start)) {
            //     $start = $event->start->date;
            // }
            // printf("%s (%s)\n", $event->getSummary(), $start);
        }
    }
}



// Customer Routes
// require '../src/routes/customers.php';
// require '../src/routes/login.php';

$app->run();
