<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/srmsng/vendor/autoload.php';
    require $_SERVER['DOCUMENT_ROOT'].'/srmsng/src/config/db.php';
    if(isset($_SESSION)){
        // echo "THIS is SESSION DATA \n";
        // echo json_encode($_SESSION)."\n";
        // echo "My Session is ".session_id()."\n";
        
        if(isset($_COOKIE['user'])){
            // Found cookie
            $jd_cookie = json_decode($_COOKIE['user']);
            $this_session = session_id();
            $jd_cookie_session = $jd_cookie->{"cookie_session"};
            // $this_username = $_SESSION['username_unhash'];
            // Try to check if session in cookie is equal to session in db.
            $sql = "SELECT session_id FROM account WHERE session_id = '$jd_cookie_session'";
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
                    // Session_id is matched.
                    // Assisgn data in cookie to $_SESSION.
                    $j_result = json_encode($result);
                    // echo($j_result);
                    $_SESSION = json_decode($_COOKIE['user'], true);

                    
                }else{
                    // Session_id is not match.
                    if(json_encode($_SESSION) == "[]"){
                        // Session is empty.
                        // Then clear the cookie and redirect to login page
                        // echo "SESSION IS NOT MATCH";
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
            echo "I'm HERERERERE";
            if(isset($_SESSION['remember'])){
                if($_SESSION['remember'] == 'on'){
                    echo "I'm HERE 3333333";
                    setcookie("user", "", time()-8000000, '/');
                    unset($_COOKIE);
                    header('location: /srmsng/public/login');
                }else{
                    echo "FROM ELSESES";
                }
            }else{
                echo "I'm HERE 22222222";
                setcookie("user", "", time()-8000000, '/');
                unset($_COOKIE);
                header('location: /srmsng/public/login');
            }
                
            
        }
    }
