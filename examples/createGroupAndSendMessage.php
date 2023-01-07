<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", "1101712345" );
define( "API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345" );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$chatIds = [
	'11001234567@c.us'
];

$resultCreate = $greenApi->groups->createGroup('GroupName', $chatIds );

if ($resultCreate->code == 200) {
	print_r($resultCreate->data).PHP_EOL;
	$resultSend = $greenApi->sending->sendMessage($resultCreate->data->chatId, 'Message text');
	if ($resultSend->code == 200)
		print_r( $resultSend->data ) . PHP_EOL;
	else
		print( $resultSend->error ) . PHP_EOL;
} else
	print( $resultCreate->error ) . PHP_EOL;
