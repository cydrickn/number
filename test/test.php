<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(E_ALL);
include "../src/Number.php";

use Cydrickn\Number\Number;

Number::setConfig(array(
    'precision' => 10,
    'round'     => true
));

$parse = Number::parseEquation("X+y*z", array('X' => '12', 'y' => '2', 'z' => '3'), false);
echo $parse . "\n";
echo Number::parseEquation("X+y*z", array('X' => '12', 'y' => '2', 'z' => '3'), true);
echo "\n";