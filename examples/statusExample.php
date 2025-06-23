<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define("ID_INSTANCE", "1101712345");
define("API_TOKEN_INSTANCE", "d75b3a66374942c5b3c019c698abc2067e151558acbd412345");

// Initialize the GREEN-API client
$greenApi = new GreenApiClient(ID_INSTANCE, API_TOKEN_INSTANCE);

// Example 1: Send a text status
$textStatusResult = $greenApi->statuses->sendTextStatus(
    "Hello from GreenAPI!",  // message
    "#FF0000",               // background color (red)
    "ARIAL",                 // font
    ["11001234567@c.us"]     // participants
);
print_r("Text Status Result:\n");
print_r($textStatusResult->data);
echo "\n\n";

// Example 2: Send a voice status
$voiceStatusResult = $greenApi->statuses->sendVoiceStatus(
    "https://example.com/voice.mp3",  // URL to voice file
    "voice_message.mp3",              // file name
    "#00FF00",                       // background color (green)
    ["11001234567@c.us"]             // participants
);
print_r("Voice Status Result:\n");
print_r($voiceStatusResult->data);
echo "\n\n";

// Example 3: Send a media status (photo or video)
$mediaStatusResult = $greenApi->statuses->sendMediaStatus(
    "https://example.com/image.jpg",  // URL to media file
    "sunset.jpg",                     // file name
    "Beautiful sunset!",              // caption
    ["11001234567@c.us"]             // participants
);
print_r("Media Status Result:\n");
print_r($mediaStatusResult->data);
echo "\n\n";

// Example 4: Get incoming statuses (last 24 hours by default)
$incomingStatuses = $greenApi->statuses->getIncomingStatuses();
print_r("Incoming Statuses (24h):\n");
print_r($incomingStatuses->data);
echo "\n\n";

// Example 5: Get incoming statuses for specific time period (60 minutes)
$incomingStatuses60min = $greenApi->statuses->getIncomingStatuses(60);
print_r("Incoming Statuses (60 minutes):\n");
print_r($incomingStatuses60min->data);
echo "\n\n";

// Example 6: Get outgoing statuses (last 24 hours by default)
$outgoingStatuses = $greenApi->statuses->getOutgoingStatuses();
print_r("Outgoing Statuses (24h):\n");
print_r($outgoingStatuses->data);
echo "\n\n";

// Example 7: Get outgoing statuses for specific time period (120 minutes)
$outgoingStatuses120min = $greenApi->statuses->getOutgoingStatuses(120);
print_r("Outgoing Statuses (120 minutes):\n");
print_r($outgoingStatuses120min->data);
echo "\n\n";

// Example 8: Get status statistics for a specific message
// Note: Replace with an actual message ID from your status
$statusStatistics = $greenApi->statuses->getStatusStatistic("3EB0C767D097B7C7C81");
print_r("Status Statistics:\n");
print_r($statusStatistics->data);
echo "\n\n";