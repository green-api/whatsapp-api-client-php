<?php

require 'vendor\autoload.php';

use GreenApi\RestApi\GreenApiClient;

define( "ID_INSTANCE", getenv("ID_INSTANCE" ));
define( "API_TOKEN_INSTANCE", getenv("API_TOKEN_INSTANCE") );

$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );

$greenApi->webhooks->startReceivingNotifications(function($typeWebhook, $body) {
	if ($typeWebhook == 'incomingMessageReceived') {
		onIncomingMessageReceived($body);
	} elseif ($typeWebhook == 'deviceInfo') {
		onDeviceInfo($body);
	} elseif ($typeWebhook == 'incomingCall') {
		onIncomingCall($body);
	} elseif ($typeWebhook == 'outgoingAPIMessageReceived') {
		onOutgoingAPIMessageReceived($body);
	} elseif ($typeWebhook == 'outgoingMessageReceived') {
		onOutgoingMessageReceived($body);
	} elseif ($typeWebhook == 'outgoingMessageStatus') {
		onOutgoingMessageStatus($body);
	} elseif ($typeWebhook == 'stateInstanceChanged') {
		onStateInstanceChanged($body);
	} elseif ($typeWebhook == 'statusInstanceChanged') {
		onStatusInstanceChanged($body);
	}
});

function onIncomingMessageReceived($body) {
	$idMessage = $body->idMessage;
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	$senderData = $body->senderData;
	$messageData =  $body->messageData;
	print($idMessage . ': At ' . $eventDate . ' Incoming from '. json_encode($senderData, JSON_UNESCAPED_UNICODE) . ' message = ' . json_encode($messageData, JSON_UNESCAPED_UNICODE)).PHP_EOL;
}

function onIncomingCall($body) {
	$idMessage = $body->idMessage;
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	$fromWho = $body->from;
	print($idMessage . ': Call from ' . $fromWho . ' at ' . $eventDate).PHP_EOL;
}

function onOutgoingAPIMessageReceived($body) {
	$idMessage = $body->idMessage;
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	$senderData = $body->senderData;
	$messageData =  $body->messageData;
	print($idMessage . ': At ' . $eventDate . ' Incoming from '. json_encode($senderData, JSON_UNESCAPED_UNICODE) . ' message = ' . json_encode($messageData, JSON_UNESCAPED_UNICODE)).PHP_EOL;
}

function onDeviceInfo( $body ) {
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	$deviceData = $body->deviceData;
	print('At ' . $eventDate . ': ' . json_encode($deviceData, JSON_UNESCAPED_UNICODE)).PHP_EOL;
}

function onOutgoingMessageReceived($body) {
	$idMessage = $body->idMessage;
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	$senderData = $body->senderData;
	$messageData =  $body->messageData;
	print($idMessage . ': At ' . $eventDate . ' Outgoing from '. json_encode($senderData, JSON_UNESCAPED_UNICODE) . ' message = ' . json_encode($messageData, JSON_UNESCAPED_UNICODE)).PHP_EOL;
}

function onOutgoingMessageStatus($body) {
	$idMessage = $body->idMessage;
	$status = $body->status;
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	print($idMessage . ': At ' . $eventDate . ' status = ' . $status).PHP_EOL;
}

function onStateInstanceChanged($body) {
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	$stateInstance = $body->stateInstance;
	print('At ' . $eventDate . ' state instance = ' . $stateInstance).PHP_EOL;
}

function onStatusInstanceChanged($body) {
	$eventDate = date('Y-m-d H:i:s', $body->timestamp);
	$statusInstance = $body->stateInstance;
	print('At ' . $eventDate . ' status instance = ' . $statusInstance).PHP_EOL;
}
