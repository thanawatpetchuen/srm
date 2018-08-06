<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/srmsng/vendor/autoload.php';
    require $_SERVER['DOCUMENT_ROOT'].'/srmsng/src/config/db.php';
    if(isset($_SESSION)){
        // Session is set.
        if(isset($_COOKIE['user'])){
            // Found cookie
            $jd_cookie = json_decode($_COOKIE['user']);
            $this_session = session_id();
            $jd_cookie_session = $jd_cookie->{"cookie_session"};
            // Try to check if session in cookie is equal to session in db.
            $sql = "SELECT session_id FROM account WHERE session_id = '$jd_cookie_session'";
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();
                $stmt = $db->query($sql);
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                if($result){
                    // Session_id is matched.
                    // Assignn data in cookie to $_SESSION.
                    $j_result = json_encode($result);
                    $_SESSION = json_decode($_COOKIE['user'], true);

                }else{
                    // Session_id is not match.
                    if(json_encode($_SESSION) == "[]"){
                        // Session is empty.
                        // Then clear the cookie and redirect to login page
                        setcookie("user", "", time()-8000000, '/');
                        unset($_COOKIE);
                        header('location: /srmsng/public/login');
                    }else{
                        // Session is not empty.
                        // But we will destroy session and cookie.
                        // In case of Hijack situation.
                        session_destroy();
                        setcookie("user", "", time()-8000000, '/');
                        unset($_COOKIE);
                        header('location: /srmsng/public/login');
                    }
                }
            } catch(PDOException $e){
                echo '{"error": {"text": '.$e->getMessage().'}';
            }
        }else{
            // Make sure to clear the COOKIE.
            if(isset($_SESSION['remember'])){
                if($_SESSION['remember'] == 'on'){
                    setcookie("user", "", time()-8000000, '/');
                    unset($_COOKIE);
                    header('location: /srmsng/public/login');
                }
            }else{
                setcookie("user", "", time()-8000000, '/');
                unset($_COOKIE);
                header('location: /srmsng/public/login');
            }
                
            
        }
    }
