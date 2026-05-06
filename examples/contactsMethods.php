<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", "1101712345" );
define( "API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345" );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$resultAdd = $greenApi->contacts->addContact('79876543210@c.us', 'John', 'Doe', true);

print_r(  $resultAdd->data );

$resultEdit = $greenApi->contacts->editContact('79876543210@c.us', 'John', 'Smith', true);

print_r(  $resultEdit->data );

$resultDelete = $greenApi->contacts->deleteContact('79876543210@c.us');

print_r(  $resultDelete->data );