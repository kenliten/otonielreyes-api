<?php

require 'vendor/autoload.php';

$filename = __DIR__."/config.json";
$stream = fopen($filename, 'r');
$config_input = json_decode(fread($stream, filesize($filename)));

$config = array();

foreach($config_input as $field => $value) {
	$config[$field] = $value;
}

$db = new MongoDB\Client($config['database_url']);
