<?php
require_once 'vendor/autoload.php';

$json = file_get_contents('package.json');
$api = new \loft\Api( $json );