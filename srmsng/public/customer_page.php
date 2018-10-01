<?php

session_start();

 
if(isset($_SESSION)){
    // Test for cookie and session
    // echo "THIS IS COOKIE ".json_encode($_COOKIE)."\n";
    // echo "THIS IS SESSION ".json_encode($_SESSION)."\n";
    // echo "SESSION ID: ".session_id()."\n";
    if(isset($_COOKIE['user'])){
       // Set current $_SESSION with COOKIE
       $_SESSION = json_decode($_COOKIE['user'], true);
       
       // Decoding cookie to local variable
       $cookie_decode = json_decode($_COOKIE['user']);
       
       // Check account type and redirect if there is not a USER 
       if($cookie_decode->{'account_type'} == "ADMIN"){
            header('location: /srmsng/public/ticket');
       }else if($cookie_decode->{'account_type'} == "USER"){
            header('location: /srmsng/public/customer');
       }else if($cookie_decode->{'account_type'} == "SUPERADMIN"){
            header('location: /srmsng/public/ticket');
       }else if($cookie_decode->{'account_type'} == "FSE"){
            header('location: /srmsng/public/fse');
        }else{
        setcookie("user", "", time()-8000000, '/');
        unset($_COOKIE);
        header("location: /srmsng/public/login");
        }   
    }else{
        // Cookie has not been set
        // Set cookie with $_SESSION value
        if($_SESSION["remember"] == "on"){
            $cookie_name = 'user';
            $cookie_session = session_id();
            $cookie_array = array("account_type" => $_SESSION["account_type"], "account_no" => $_SESSION["account_no"], "account_status" => $_SESSION["account_status"],
                "username_unhash" => $_SESSION["username_unhash"], "cookie_session" => $cookie_session, "remember" => $_SESSION["remember"]);
            $cookie_array_encode = json_encode($cookie_array);
            $cookie_value = json_encode($_SESSION);
            if($cookie_value == "[]"){
                // Empty cookie that means $_SESSION is empty too
                echo "From cookie value";
                header("location: /srmsng/public/login");
            }
            setcookie($cookie_name, $cookie_array_encode, time() + 43200, "/"); // 12 Hours
            $cookie_decode = json_decode($cookie_value); // Used by html tag to display TEXT
        }
        header("location: /srmsng/public/announcement");
 
   }

}
    
?>
