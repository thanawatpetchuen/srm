<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Slim\Views\PhpRenderer;


session_start();

require '../../../vendor/autoload.php';
require '../../../src/config/db.php';
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

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("./templates");



// Redirect to login page

$app->get('/{id}', function ($request, $response, $args) {
    // return json_encode($args);
    $templateVariables = [
        "title" => "Title"
    ];
    return $this->renderer->render($response, "/temp.php", $args);
});

$app->get('/api/getnews/{start}/{stop}', function(Request $request, Response $response, $args){
    $sql = "SELECT id, title, content, author, DATE_FORMAT(date, '%d %M %Y (%H:%i)') AS date, image FROM news ORDER BY date DESC LIMIT ".$args['start'].",".$args['stop'];
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
           $db = null;
           echo '{"error": {"text": '.$e->getMessage().'}';
       }
});

$app->get('/api/countnews', function(Request $request, Response $response, $args){
    $sql = "SELECT COUNT(id) AS count FROM news";
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
           $db = null;
           echo '{"error": {"text": '.$e->getMessage().'}';
       }
});

$app->get('/api/getnotices', function(Request $request, Response $response, $args){
    $sql = "SELECT id, title, DATE_FORMAT(date, '%d %M %Y (%H:%i)') AS date, type FROM notice ORDER BY date DESC";
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
        $db = null;
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

$app->post('/api/addnotice', function(Request $request, Response $response, $args){
    $title = $request->getParam('title');
    $type = $request->getParam('type');
    $post_time = date('Y-m-d H:i:s', time());
    $title_esc = addslashes($title);

    $sql = "INSERT INTO notice (title, type, date) VALUES ('{$title_esc}', '{$type}', '{$post_time}')";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $e->getmessage();
    }

    return $response->withRedirect("/srmsng/public/announcement");
});



$app->post('/api/post', function(Request $request, Response $response, $args){
    $title = $request->getParam('title');
    $author = $request->getParam('author');
    $content = $request->getParam('content');
    $image = $request->getParam('image');
    $post_time = date('Y-m-d H:i:s', time());
    $title_esc = addslashes($title);
    $content_esc = addslashes($content);

    $target_dir = $_SERVER['DOCUMENT_ROOT']."/srmsng/uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
    if(basename($_FILES["fileToUpload"]["name"]) == null){
        
        $image = "https://increasify.com.au/wp-content/uploads/2016/08/default-image.png";
    }else{
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        // if ($_FILES["fileToUpload"]["size"] > 500000) {
        //     echo "Sorry, your file is too large.";
        //     $uploadOk = 0;
        // }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        // return $response->withRedirect("/srmsng/public/announcement");
        echo $image;
        $image = "/srmsng/uploads/".basename($_FILES["fileToUpload"]["name"]);
    }
    
    $sql = "INSERT INTO news (title, content, author, date, image) VALUES ('{$title_esc}', '{$content_esc}', '{$author}', '{$post_time}', '{$image}')";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $e->getmessage();
    }

    return $response->withRedirect("/srmsng/public/announcement");
});

$app->delete('/api/deleteNews', function(Request $request, Response $response){
    $id = $request->getParam("id");
    $sql = "DELETE FROM news WHERE id = {$id}";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $e->getmessage();
    }
});

$app->delete('/api/deleteNotice', function(Request $request, Response $response){
    $id = $request->getParam("id");
    $sql = "DELETE FROM notice WHERE id = {$id}";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
    } catch(PDOException $e) {
        $db = null;
        return $e->getmessage();
    }
});


$app->post('/api/upload', function(Request $request, Response $response, $args){
    $target_dir = $_SERVER['DOCUMENT_ROOT']."/srmsng/uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    // if ($_FILES["fileToUpload"]["size"] > 500000) {
    //     echo "Sorry, your file is too large.";
    //     $uploadOk = 0;
    // }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

});




// $app->get('/{ids}', function ($request, $response, $args) {
//     // echo json_encode($args);
//     $templateVariables = [
//         "title" => "Title"
//     ];
//     return $this->renderer->render($response, "/temp.php", $args);
// });


// $app->get('/', function (Request $request, Response $response) {

//     // $response->getBody()->write("Hello");
//     // return "ASD";
//     return $response->withRedirect("/srmsng/public/announcement");
// });

$app->run();
