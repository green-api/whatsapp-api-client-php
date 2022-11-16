<?php
require 'vendor\autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", getenv("ID_INSTANCE" ));
define( "API_TOKEN_INSTANCE", getenv("API_TOKEN_INSTANCE") );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$result = $greenApi->sending->sendFileByUpload(
	'11001234567@c.us',
	'C:\Games\PicFromDisk.png',
	'PicFromDisk.jpg',
	'Picture from disk'
);

print_r(  $result->data );
