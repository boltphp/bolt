<?php

require_once __DIR__."/../vendor/autoload.php";

date_default_timezone_set('UTC');

error_reporting(E_ALL);

class Test extends PHPUnit_Framework_TestCase {

    public function createApp() {
        return include __DIR__."/../src/bootstrap.php";
    }

}