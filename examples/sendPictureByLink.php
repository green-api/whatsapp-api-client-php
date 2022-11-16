<?php
require 'vendor\autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", getenv("ID_INSTANCE" ));
define( "API_TOKEN_INSTANCE", getenv("API_TOKEN_INSTANCE") );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$result = $greenApi->sending->sendFileByUrl(
	'11001234567@c.us',
	'https://www.google.ru/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
	'googlelogo_color_272x92dp.png',
	'Google logo'
);

print_r(  $result->data );
