<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", "1101712345" );
define( "API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345" );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$result = $greenApi->sending->sendInteractiveButtons(
	'11001234567@c.us',
	'Choose an action',
	[
		['type' => 'url',  'buttonId' => '1', 'buttonText' => 'Visit website', 'url' => 'https://green-api.com'],
		['type' => 'call', 'buttonId' => '2', 'buttonText' => 'Call us',       'phoneNumber' => '11001234567'],
		['type' => 'copy', 'buttonId' => '3', 'buttonText' => 'Copy code',     'copyCode' => 'PROMO2024'],
	],
	'Green API',
	'Powered by Green API'
);

print_r( $result->data );
