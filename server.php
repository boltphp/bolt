<?php

// if this request isn't from the internal
// server assume they want to run the server
if (php_sapi_name() === 'cli') {
    $opt = getopt("h::p::");
    $host = isset($opt['h']) ? $opt['h'] : 'localhost';
    $port = isset($opt['p']) ? $opt['p'] : 8000;
    $cmd = "php -S {$host}:{$port} -t src/static server.php";
    echo `$cmd`;
    exit;
}

// if this request is using the cli server
if (php_sapi_name() === 'cli-server') {

    // get the uri path
    $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // append assets to it
    $static = "./src/static{$uri}";

    // if this is an assets request
    // do not use internal routing
    if ($uri !== '/' and file_exists($static)){
        return false;
    }

}


// we need to bootsrap
$bolt = require "./src/bootstrap.php";

// error
if (!is_a($bolt, 'bolt\application')) {
    header("Content-Type:text/plain", true, 500);
    exit("No bolt application passed from bootstrap");
}

// run our instance
$bolt->run();