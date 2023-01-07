<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", "1101712345" );
define( "API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345" );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$result = $greenApi->sending->sendFileByUrl(
	'11001234567@c.us',
	'https://www.google.ru/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
	'googlelogo_color_272x92dp.png',
	'Google logo'
);

print_r(  $result->data );
