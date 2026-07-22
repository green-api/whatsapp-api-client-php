<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", "1101712345" );
define( "API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345" );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$result = $greenApi->sending->sendPoll(
	'11001234567@c.us',
	'What is your favorite programming language?',
	[
		['optionName' => 'PHP'],
		['optionName' => 'Python'],
		['optionName' => 'JavaScript'],
	]
);

print_r( $result->data );
