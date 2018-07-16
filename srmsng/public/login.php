<!DOCTYPE html>
<?php 
    session_start();
    
    // echo json_encode($_SESSION);
    // Check if $_SESSION is set
    if(isset($_SESSION)){
        
        // Test for cookie and session
        // echo json_encode($_SESSION)."\n";
        // echo json_encode($_COOKIE)."\n";
        // echo session_id()."\n";
        // echo json_encode($_SESSION);

        if(isset($_COOKIE['user'])){
            // Cookie is set
            if(json_encode($_SESSION) == "[]"){
                // $_SESSION is empty but cookie is not empty

                // Decode cookie to check status of account in the cookie
                $test_cookie = json_decode($_COOKIE['user']);
                if($test_cookie->{"account_status"} == "LOGIN"){
                    // Cookie is not LOGOUT, allow to login
                    if($test_cookie->{"account_type"} == "USER"){
                        // You're USER
                        // Redirect to customer.php
                        header('location: /srmsng/public/customer');
                    }else if($test_cookie->{"account_type"} == "ADMIN"){
                        // Redirect to admin_page.php
                        // echo "From admin";
                        // session_destroy();
                        // session_unset();
                        // setcookie("user", "", time()-8000000, '/');
                        // unset($_COOKIE);

                        header('location: /srmsng/public/admin_page');
                    }else if($test_cookie->{"account_type"} == "SUPERADMIN"){
                        header('location: /srmsng/public/admin_page');
                    }
                }else{
                    // Clear the cookie
                    setcookie("user", "", time()-8000000, '/');
                    unset($_COOKIE);

                }
            }else{
                // $_SESSION is not empty maybe from logout with current session
                try{ 
                    // Test for cookie and session
                    // echo "THIS IS SESSION ".json_encode($_SESSION)."\n";
                    // echo "THIS IS COOKIE ".json_encode($_COOKIE)."\n";

                    // Decode cookie to check account type and Redirect
                    $cookie_decode = json_decode($_COOKIE['user']);
                    if($cookie_decode->{'account_type'} == "USER"){
                        // You're USER
                        header('location: /srmsng/public/customer');
                    }else if($cookie_decode->{'account_type'} == "ADMIN"){
                        // You're ADMIN
                        header('location: /srmsng/public/admin_page');
                    }else if($cookie_decode->{'account_type'} == "SUPERADMIN"){
                        header('location: /srmsng/public/admin_page');
                    }else{
                        // "You're not LOGIN";
                        // header("location: /srmsng/public/login.php");    
                    }
                }catch(Exception $e){
                    echo "ERROR";
                }
            }
        
        }
        if(isset($_SESSION['account_status'])){
            if($_SESSION['account_status'] == "LOGIN"){
                if($_SESSION['account_type'] == "USER"){
                    header("location: /srmsng/public/customer_page");
                }else if($_SESSION['account_type'] == "ADMIN"){
                    header("location: /srmsng/public/admin_page");
                }else if($_SESSION['account_type'] == "SUPERADMIN"){
                    header("location: /srmsng/public/admin_page");
                }
                // header("location: /srmsng/public/admin_page");
            } 

        }
    }
  
?>
<html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/animate.css">
</head>

<body class="login">
    <div class="login-window">
        <div class="logo-container">
            <img src="../src/image/sng.png" alt="Avatar" class="avatar">
        </div>
        <form method="POST" id="loginForm">
            <div class="alert alert-danger alert-dismissible" id="login-error" role="alert">
                <span class="alert-content"></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="form-group">
                <label for="uname">
                    <b>Username</b>
                </label>
                <input type="text" placeholder="Enter Username" name="username" class="form-control" required>

            </div>
            <div class="form-group">
                <label for="psw">
                    <b>Password</b>
                </label>
                <input type="password" placeholder="Enter Password" name="password" class="form-control" id="passwordField" required>
                <div class="invalid-feedback" id="invalid-feedback">
                    Wrong password
                </div>
            </div>
            <div class="form-group form-check-inline">
                <input type="checkbox" checked="checked" name="remember" class="form-check-input">
                <label class="form-check-label">Remember me</label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" id="loginBtn">Login</button>
                <a href="/srmsng/public/recover.html" class="btn btn-link">Forgot password?</a>
            </div>
        </form>
    </div>
</body>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script src="js/login.js"></script>
</html>