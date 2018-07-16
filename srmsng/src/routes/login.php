<?php
$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->post('/login', function (Request $request, Response $response) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT account_no, level_account, account_status, attempt, is_lock FROM account WHERE username = '$username' and password = '$password'";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password',  $password);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        // $response->getBody()->write(json_encode($result));
        $response = json_encode($result);
        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
    return $response;
});

$app->post('/login/recover', function (Request $request, Response $response) {
    $email = $_POST["email"];
    $message = $_POST["message"];
    $status = "UNREAD";

    $sql = "INSERT INTO message (email, message, status) VALUES
    (:email, :message, :status)";
    
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message',  $message);
        $stmt->bindParam(':status',  $status);

        $stmt->execute();
      
        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
    return $response->withRedirect('/srmsng/public/login.html/');
});
