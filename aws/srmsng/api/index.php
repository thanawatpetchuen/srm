<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Slim\Views\PhpRenderer;

// use Slim\App;
use Slim\Middleware\TokenAuthentication;
// require_once("app/Auth.php");

session_start();

require '../vendor/autoload.php';
require '../src/config/db.php';
include './app/Auth.php';


// require 'login.html';

function checkUserType($type){
    $this_type = $_SESSION["account_type"];
    if($this_type == $type){
        return true;
    }else{
        return false;
    }
}

function checkAuth(){
    if(isset($_SESSION['account_status'])){
        return true;
    }else{
        return false;
    }
}

$c = new \Slim\Container(); //Create Your container

//Override the default Not Found Handler
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withRedirect('/srmsng/public/404.html');

    };
};



$authenticator = function($request, TokenAuthentication $tokenAuth){

    # Search for token on header, parameter, cookie or attribute
    $token = $tokenAuth->findToken($request);

    # Your method to make token validation
    $auth = new \app\Auth();
    /**
     * Verify if token is valid on database
     * If token isn't valid, must throw an UnauthorizedExceptionInterface
     */
    echo(json_encode($auth->getUserByToken($token)));
    // $auth);
    # If occured ok authentication continue to route
    # before end you can storage the user informations or whatever

};

$app = new \Slim\App($c);

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("./templates");

$app->add(new TokenAuthentication([
    'path' => '/v1/getUserInfo',
    'authenticator' => $authenticator,
    'secure' => false,
    'parameter' => 'api_key'
]));




$app->get('/v1/userinfo', function(Request $request, Response $response, $args){
    if(checkAuth()){
        $info = array();
        $cookie_info = array();
        $info["session"] = $_SESSION;
        $cookie_info["user"] = json_decode($_COOKIE["user"]);
        $cookie_info["PHPSESSID"] = $_COOKIE["PHPSESSID"];
        $info["cookie"] = $cookie_info;
        return $response->withJson($info, 200);
    }else{
        return $this->renderer->render($response, "/unauth.php")->withStatus(401)->withHeader('Content-Type', 'text/html');
    };
});

$app->get('/v1/generateKey', function(Request $request, Response $response, $args){
    if(checkAuth()){
        $api_key = array();
        $key = substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 21);
        $api_key["api_key"] = hash("sha256", $key);
        return $response->withJson($api_key, 201);
        // $res = $response->getBody()->write('Asd');
        // return $res;
    }else{
        return $this->renderer->render($response, "/unauth.php")->withStatus(401)->withHeader('Content-Type', 'text/html');
    };
});

$app->post('/v1/maintenance', function(Request $request, Response $response, $args){
    $my_file = $_SERVER['DOCUMENT_ROOT']."/srmsng/settings/maintenance.enable";
    $myfile = fopen($my_file, "w") or die("Unable to open file!");
    $txt = "Just a moment!";
    fwrite($myfile, $txt);
    fclose($myfile);
});

$app->delete('/v1/maintenance', function(Request $request, Response $response, $args){
    $file = $_SERVER['DOCUMENT_ROOT']."/srmsng/settings/maintenance.enable";
    if (!unlink($file))
      {
      echo ("Error deleting $file");
      }
    else
      {
      echo ("Deleted $file");
      }
});



// $app->get('/books/:one/:two', function ($one, $two) {
//     echo "The first parameter is " . $one;
//     echo "The second parameter is " . $two;
// });

// $app->get('/books/:one', function ($one) {
//     echo "BOOKE";
// });

// $app->get('/books', function ($one, $two) {
//     echo "The first parameter is ";
// });

// $app->get('/hello/{name}', function (Request $request, Response $response) {
//     $name = $request->getAttribute('name');
//     $response->getBody()->write("Hello, $name");

//     return $response;
// });

// $app->get('/', function (Request $request, Response $response) {

//     $response->getBody()->write("Hello");

//     return $response;
// });

$app->run();
