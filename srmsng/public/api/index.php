<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


session_start();

require '../../vendor/autoload.php';
require '../../src/config/db.php';
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
$app->get('/:id', function($id){
    return "FUCK YEAH".$id ;
});

$app->get('/books/:one/:two', function ($one, $two) {
    echo "The first parameter is " . $one;
    echo "The second parameter is " . $two;
});

$app->get('/books/:one', function ($one) {
    echo "BOOKE";
});

$app->get('/books', function ($one, $two) {
    echo "The first parameter is ";
});

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->get('/', function (Request $request, Response $response) {

    $response->getBody()->write("Hello");

    return $response;
});

$app->run();
