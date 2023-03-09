<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", "1101712345" );
define( "API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345" );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE, 'http://127.0.0.1:8080' );

$result = $greenApi->sending->uploadFile(
	'C:\Games\PicFromDisk.png'
);

print_r(  $result->data );

$result = $greenApi->sending->sendFileByUrl(
	'11001234567@c.us',
	$result->data->urlFile,
	'googlelogo_color_272x92dp.png',
	'Google logo'
);

print_r(  $result->data );
