<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", "1101712345" );
define( "API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345" );

$greenApi = new GreenApiClient(ID_INSTANCE, API_TOKEN_INSTANCE);

$result = $greenApi->sending->sendPoll('11001234567@c.us', 'Please choose the color:',
    array(array('optionName'=>'green'), array('optionName'=>'red'), array('optionName'=>'blue')));

print_r($result->data);
