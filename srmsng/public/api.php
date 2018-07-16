<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


session_start();

require '../vendor/autoload.php';
require '../src/config/db.php';
// require 'login.html';

$app = new \Slim\App;


// Login
$app->post('/login', function (Request $request, Response $response) {
    $username = hash('sha256', $_POST["username"]);
    $password = hash('sha256', $_POST["password"]);
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
                    $_SESSION['account_type'] = $jsonArray['account_type'];
                    $_SESSION['account_no'] = $jsonArray['account_no'];
                    $_SESSION['username'] = $jsonArray['username'];
                    $_SESSION['username_unhash'] = $_POST["username"];
                    $attempt = 10;
                    $sql = "UPDATE account SET  attempt = :set_attempt, account_status = :set_status WHERE username = '$username'";    
                    $stmt = $db->prepare($sql);
                    $session_status = 'LOGIN';
                    $stmt->bindParam(':set_attempt', $attempt);
                    $stmt->bindParam(':set_status', $session_status);
                    $stmt->execute();
                    if ($jsonArray['account_type'] == 'USER'){
                        $success_json = array('statuscode' => '111', 'description' => '/srmsng/public/customer.php');
                        return json_encode($success_json);
                    }else{
                        $success_json =array('statuscode' => '112', 'description' => '/srmsng/public/admin_page.php');
                        return json_encode($success_json);
                    }
                }else{
                    $err_json = array('statuscode' => '010', 'description' => 'Your account has been login on another device.');
                    return json_encode($err_json); 
                }
            }else{
                $err_json = array('statuscode' => '011', 'description' => 'Number of attempts exceed limit.');
                return json_encode($err_json); 
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
                    $err_json = array('statuscode' => '101', 'attempt' => 0, 'description' => 'Invalid password');
                }
                if ($attempt == 0){
                    $sql = "UPDATE account SET is_lock = 1 WHERE username = '$username'";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                }
            }else{
                $err_json = array('statuscode' => '000', 'description' => 'Invalid username and password');
            }
            return json_encode($err_json);   
        }
    }catch(PDOException $e){
        $err_json = array('statuscode' => '001', 'description' => $e->getMessage());
        return json_encode($err_json);     
    }
});



// Logout
$app->post('/logout', function (Request $request, Response $response) {
    if (isset($_SESSION['account_type'])){
        $date = date('m/d/Y h:i:s a', time());
        $account_no = $_SESSION['account_no'];
        $sql = "UPDATE account SET account_status = :set_status, last_login = :set_date WHERE account_no = '$account_no'";
        $session_status = 'LOGOUT';
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->prepare($sql);

            $stmt->bindParam(':set_status', $session_status);
            $stmt->bindParam(':set_date', $date);
            
            $stmt->execute();

            
            session_destroy();
            return "/srmsng/public/login.php";

        }catch(PDOException $e){
            return "/srmsng/public/login.php";
        }
    }else{
        return "/srmsng/public/login.php";
    }
});


// Recover password in login section
$app->post('/login/recover', function (Request $request, Response $response) {
    if(isset($_POST["email"])){
        $email = $_POST["email"];
        $message = $_POST["message"];
        $username = $_POST["username"];
        $status = "UNREAD";
        $date = date('m/d/Y h:i:s a', time());

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
            
            return 'SUCCESS';
            
        } catch(PDOException $e){
            return $e->getmessage();
        }
    }else{
        return '/srmsng/public/recover.html';
    }
});

// Get all customers
$app->get('/api/customers', function(Request $request, Response $response){
    // $acc_no = $_SESSION['account_no'];
    $sql = "SELECT * FROM asset_tracker";
    
        // if ($_SESSION['account_type'] == 'ADMIN'){
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);
                $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
                $db = null;
                echo json_encode($customers);
            } catch(PDOException $e){
                echo '{"error": {"text": '.$e->getMessage().'}';
            }
        // }
});

// Get single customer service request
$app->get('/api/customer/{username}', function(Request $request, Response $response){
    $username = hash('sha256', $request->getAttribute('username'));

    $sql = "SELECT * FROM account INNER JOIN asset_tracker ON asset_tracker.customer_no = account.account_no WHERE username='$username'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        
        $stmt = $db->query($sql);
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Send service request in customer section
$app->post('/api/customer/request', function(Request $request, Response $response){
    $sng_code = $request->getParam('sngcode');
    $name = $request->getParam('name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $problem = $request->getParam('problem');

    $sql = "INSERT INTO srm_request (sng_code, name, phone_number, email, asset_problem) VALUES
    (:set_code, :set_name, :set_phone, :set_email, :set_problem)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_code', $sng_code);
        $stmt->bindParam(':set_name', $name);
        $stmt->bindParam(':set_phone', $phone);
        $stmt->bindParam(':set_email', $email);
        $stmt->bindParam(':set_problem', $problem);
        
        $stmt->execute();
        return "OK";
    }catch(PDOException $e){
        return $e->getmessage();
    }
});

// Add asset to customer in admin section
$app->post('/api/admin/addasset', function(Request $request, Response $response){
    $sngcode = $request->getParam('sng_code');
    $customer_no = $request->getParam('customer_no');
    $itemnumber = $request->getParam('itemnumber');
    $model = $request->getParam('model');
    $kva = $request->getParam('kva');
    $serial = $request->getParam('serial');
    $battery = $request->getParam('battery');
    $quantity = $request->getParam('quantity');
    $location = $request->getParam('location');
    $city = $request->getParam('city');
    $province = $request->getParam('province');
    $contactname = $request->getParam('contactname');
    $contactnumber = $request->getParam('contactnumber');
    $startwarranty = $request->getParam('startwarranty');
    $endwarranty = $request->getParam('endwarranty');
    $pmyear = $request->getParam('pmyear');
    $nextpm = $request->getParam('nextpm');
    $fse = $request->getParam('fse');
    $status = $request->getParam('status');

    $sql = "INSERT INTO asset_tracker (sngcode, customer_no, serial, itemnumber, model, kva, battery, quantity, location, city, province, contactname, contactnumber, startwarranty, endwarranty, pmyear, nextpm, fse, status) VALUES
    (:set_sngcode, :set_customer_no, :set_serial, :set_itemnumber, :set_model, :set_kva, :set_battery, :set_quantity, :set_location, :set_city, :set_province, :set_contactname, :set_contactnumber, :set_startwarranty, :set_endwarranty, :set_pmyear, :set_nextpm, :set_fse, :set_status)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_sngcode', $sngcode);
        $stmt->bindParam(':set_customer_no', $customer_no);
        $stmt->bindParam(':set_serial', $serial);
        $stmt->bindParam(':set_itemnumber', $itemnumber);
        $stmt->bindParam(':set_model', $model);
        $stmt->bindParam(':set_kva', $kva);
        $stmt->bindParam(':set_battery', $battery);
        $stmt->bindParam(':set_quantity', $quantity);
        $stmt->bindParam(':set_location', $location);
        $stmt->bindParam(':set_city', $city);
        $stmt->bindParam(':set_province', $province);
        $stmt->bindParam(':set_contactname', $contactname);
        $stmt->bindParam(':set_contactnumber', $contactnumber);
        $stmt->bindParam(':set_startwarranty', $startwarranty);
        $stmt->bindParam(':set_endwarranty', $endwarranty);
        $stmt->bindParam(':set_pmyear', $pmyear);
        $stmt->bindParam(':set_nextpm', $nextpm);
        $stmt->bindParam(':set_fse', $fse);
        $stmt->bindParam(':set_status', $status);
        $stmt->execute();

        return "Success";

    }catch(PDOException $e){
        return $e->getmessage();
    }
});


// Edit customer asset
$app->put('/api/admin/updateasset', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $sngcode = $request->getParam('sng_code');
    $customer_no = $request->getParam('customer_no');
    $itemnumber = $request->getParam('itemnumber');
    $model = $request->getParam('model');
    $kva = $request->getParam('kva');
    $serial = $request->getParam('serial');
    $battery = $request->getParam('battery');
    $quantity = $request->getParam('quantity');
    $location = $request->getParam('location');
    $city = $request->getParam('city');
    $province = $request->getParam('province');
    $contactname = $request->getParam('contactname');
    $contactnumber = $request->getParam('contactnumber');
    $startwarranty = $request->getParam('startwarranty');
    $endwarranty = $request->getParam('endwarranty');
    $pmyear = $request->getParam('pmyear');
    $nextpm = $request->getParam('nextpm');
    $fse = $request->getParam('fse');
    $status = $request->getParam('status');

    $sql = "UPDATE asset_tracker SET 
                customer_no   = :set_customer_no,  
                itemnumber    = :set_itemnumber, 
                model         = :set_model, 
                kva           = :set_kva, 
                serial        = :set_serial, 
                battery       = :set_battery, 
                quantity      = :set_quantity, 
                location      = :set_location, 
                city          = :set_city, 
                province      = :set_province, 
                contactname   = :set_contactname, 
                contactnumber = :set_contactnumber, 
                startwarranty = :set_startwarranty, 
                endwarranty   = :set_endwarranty, 
                pmyear        = :set_pmyear, 
                nextpm        = :set_nextpm, 
                fse           = :set_fse, 
                status        = :set_status
            WHERE sngcode = '$sngcode'";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_customer_no', $customer_no);
        $stmt->bindParam(':set_itemnumber', $itemnumber);
        $stmt->bindParam(':set_model', $model);
        $stmt->bindParam(':set_kva', $kva);
        $stmt->bindParam(':set_serial', $serial);
        $stmt->bindParam(':set_battery', $battery);
        $stmt->bindParam(':set_quantity', $quantity);
        $stmt->bindParam(':set_location', $location);
        $stmt->bindParam(':set_city', $city);
        $stmt->bindParam(':set_province', $province);
        $stmt->bindParam(':set_contactname', $contactname);
        $stmt->bindParam(':set_contactnumber', $contactnumber);
        $stmt->bindParam(':set_startwarranty', $startwarranty);
        $stmt->bindParam(':set_endwarranty', $endwarranty);
        $stmt->bindParam(':set_pmyear', $pmyear);
        $stmt->bindParam(':set_nextpm', $nextpm);
        $stmt->bindParam(':set_fse', $fse);
        $stmt->bindParam(':set_status', $status);

        $stmt->execute();

        return "SUCCESS";

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
        

// Get all customer request
$app->get('/api/admin/request', function(Request $request, Response $response){
     $sql = "SELECT * FROM srm_request LEFT JOIN ticket";
    // $sql = "SELECT * FROM srm_request";
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $request = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            return json_encode($request);
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
        
});

// Get all user
$app->get('/api/admin/getuser', function(Request $request, Response $response){

    $sql = "SELECT account_status, account_type, is_lock, last_login, account_name, username_tag FROM account 
    LEFT JOIN customers ON customers.customer_no = account.account_no";

        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->query($sql);
            $request = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            return json_encode($request);
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
        
});


// Get FSE
$app->get('/api/admin/getfse', function (Request $request, Response $response) {
    $sql = "SELECT engname FROM fse";

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
        echo '{"error": {"text": '.$e->getMessage().'}';
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
        $message = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return json_encode($message);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


// 
$app->put('/admin/recover/reset', function (Request $request, Response $response) {
    $username = $request->getParam('username');
    $new_password = $request->getParam('new_password');
    $email = $request->getParam('email');
    $hash_password = hash('sha256', $new_password);
    // the message
    $msg = "New Password: " . $new_password;
    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg, 70);

    $sql = "UPDATE account SET password = :set_password WHERE username_tag = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':set_password', $hash_password);
        $stmt->execute();

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }

    $sql = "UPDATE recover_msg SET status = :set_status WHERE username = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $status = 'READ';
        $stmt->bindParam(':set_status', $status);
        $stmt->execute();
        // return 'SUCCESS'.$new_password;
        // Send email to customer
        $res = smtp($email, "SNG Recovery Password", $msg);
        return $res;

    } catch(PDOException $e){
        return '{"error": {"text": '.$e->getMessage().'}';
    }

});


// Update status account
$app->put('/api/admin/account', function(Request $request, Response $response){
    $username = $request->getParam('username');

    $sql = "UPDATE account SET is_lock = Not is_lock WHERE username_tag = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return "SUCCESSsss";

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// DeAuth
$app->put('/api/admin/deauth', function(Request $request, Response $response){
    $username = $request->getParam('username');
    $status = "LOGOUT";
    $white = "";
    $sql = "UPDATE account SET account_status = '$status', session_id = '$white' WHERE username_tag = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return "SUCCESSsss";

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// DeAuthAll
$app->put('/api/admin/deauthall', function(Request $request, Response $response){
    // $username = $request->getParam('username');
    $status = "LOGOUT";
    $white = "";
    $sql = "UPDATE account SET account_status = '$status', session_id = '$white'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return "SUCCESSj";

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Get all customers
$app->post('/checkLogin', function(Request $request, Response $response){
    // $acc_no = $_SESSION['account_no'];
    $username = $request->getParam('username');
    // $password = hash('sha256', $_POST["password"]);
    $session_ids = $request->getParam('session_ids');
    // echo $username." ".$session_ids;

    $sql = "SELECT username, account_status FROM account WHERE username_tag = '$username' and session_id = '$session_ids'";
    
        // if ($_SESSION['account_type'] == 'ADMIN'){
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);

                $result = $stmt->fetch(PDO::FETCH_OBJ);
                // echo json_encode($result); 
                // $db = null;
                if($result){
                    // echo json_encode($result);
                    return "SUCCESS";
                }else{
                    echo "SESSION IS NOT MATCH";
                }
            } catch(PDOException $e){
                echo '{"error": {"text": '.$e->getMessage().'}';
            }
        // }
});

// Get all customers
$app->get('/getOnlineUser', function(Request $request, Response $response){
    // $acc_no = $_SESSION['account_no'];
    // $username = $request->getParam('username');
    // // $password = hash('sha256', $_POST["password"]);
    // $session_ids = $request->getParam('session_ids');
    // echo $username." ".$session_ids;


    $sql = "SELECT username, account_status FROM account WHERE account_status = 'LOGIN'";
    
        // if ($_SESSION['account_type'] == 'ADMIN'){
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                // echo json_encode($result); 
                // $db = null;
                if($result){
                    echo json_encode(count($result));
                    // return "SUCCESS";
                }else{
                    // echo "0";
                    echo "No Online";
                }
            } catch(PDOException $e){
                echo '{"error": {"text": '.$e->getMessage().'}';
            }
        // }
});


$app->get('/api/admin/log', function(Request $request, Response $response){
    // $acc_no = $_SESSION['account_no'];
    $sql = "SELECT * FROM system_log";
    
    
        // if ($_SESSION['account_type'] == 'ADMIN'){
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);
                $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
                $data_arr = array("data" => $customers);
                $db = null;
                echo json_encode($data_arr);
            } catch(PDOException $e){
                echo '{"error": {"text": '.$e->getMessage().'}';
            }
        // }
});

// DeAuth ALL
$app->put('/api/admin', function(Request $request, Response $response){
    // $username = $request->getParam('username');
    $status = "LOGOUT";
    $sql = "UPDATE account SET account_status = '$status'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return "SUCCESSsss";

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});



// Add srm user
$app->post('/api/adduser', function(Request $request, Response $response){
    $acc_no = $request->getParam('account_no');
    $username_tag = $request->getParam('username');
    $username = hash('sha256', $username_tag);
    $password = hash('sha256', $request->getParam('password'));
    $account_type = $request->getParam('account_type');
    $status = 'LOGOUT';
    $attempt = 0;
    $is_lock = false;

    $sql = "INSERT INTO account (account_no, username, password, account_status, attempt, is_lock, account_type, username_tag) VALUES 
    (:set_account, :set_user, :set_pass, :set_status, :set_attempt, :set_lock, :set_type, :set_tag)";
    
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
        return "ACCOUNT HAS BEEEN CREATED";
    } catch(PDOException $e){
        echo $e->getMessage();
    }
});




// Update Customer
// $app->put('/api/customer/request/{id}', function(Request $request, Response $response){
//     $id = $request->getAttribute('id');
//     $name = $request->getParam('name');
//     $phone = $request->getParam('phone');
//     $email = $request->getParam('email');
//     $state = $request->getParam('state');

//     $sql = "UPDATE customers SET
// 				first_name 	= :first_name,
// 				last_name 	= :last_name,
//                 phone		= :phone,
//                 email		= :email,
//                 address 	= :address,
//                 city 		= :city,
//                 state		= :state
// 			WHERE id = $id";

//     try{
//         // Get DB Object
//         $db = new db();
//         // Connect
//         $db = $db->connect();

//         $stmt = $db->prepare($sql);

//         $stmt->bindParam(':first_name', $first_name);
//         $stmt->bindParam(':last_name',  $last_name);
//         $stmt->bindParam(':phone', $phone);
//         $stmt->bindParam(':email', $email);
//         $stmt->bindParam(':address', $address);
//         $stmt->bindParam(':city', $city);
//         $stmt->bindParam(':state', $state);

//         $stmt->execute();

//         echo '{"notice": {"text": "Customer Updated"}';

//     } catch(PDOException $e){
//         echo '{"error": {"text": '.$e->getMessage().'}';
//     }
// });

// Delete account
$app->delete('/api/admin/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM account WHERE account_no = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return "SUCCESS DELETE";

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

function system_log($msg) {
    $sql = "INSERT INTO system_log (user, level, action, date_time) VALUES (:set_user, :set_level, :set_action, :set_date_time)";
    $user =  $_SESSION['username_unhash'];
    $level = $_SESSION['account_type'];
    $date_time = date('m/d/Y h:i:s a', time());

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':set_user', $user);
        $stmt->bindParam(':set_level', $level);
        $stmt->bindParam(':set_action', $msg);
        $stmt->bindParam(':set_date_time', $date_time);

        $stmt->execute();
        return "SUCCESS";
    } catch(PDOException $e){
        echo $e->getMessage();
    }
}



function smtp($subject, $body) {

    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'synergize.provider@gmail.com';                 // SMTP username
        $mail->Password = '27042537poO';                           // SMTP password
        $mail->SMTPSecure = 'TLS;';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('synergize.provider@gmail.com', 'Synergize Service');
        $mail->addAddress('job.masker@gmail.com');               // Name is optional
        $mail->addReplyTo('synergize.provider@gmail.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return 'SUCCESS';
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
    }
}




// Customer Routes
// require '../src/routes/customers.php';
// require '../src/routes/login.php';

$app->run();