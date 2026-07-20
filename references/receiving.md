# Receiving & webhooks — PHP SDK

Sources: `src/tools/Receiving.php`, `src/tools/Webhooks.php`  
Docs: https://green-api.com/en/docs/api/receiving/

Two modes ([Before you start](https://green-api.com/en/docs/before-start/)):

1. **HTTP API polling** — `webhookUrl` empty; use `receiveNotification` / `deleteNotification`
2. **Webhook Endpoint** — public HTTPS URL in `webhookUrl`; GREEN-API POSTs notifications

They are mutually exclusive for receive-from-queue: if `webhookUrl` is set, ReceiveNotification returns an error (docs).

---

## `receiving->receiveNotification`

**Docs:** https://green-api.com/en/docs/api/receiving/technology-http-api/ReceiveNotification/

```php
public function receiveNotification(): stdClass
```

SDK signature has **no** `receiveTimeout` argument (docs allow query `receiveTimeout` 5–60 s; default 5 s on API).

**Success with notification (`data`):**

| Field | Type | Description |
|-------|------|-------------|
| `receiptId` | integer | Pass to `deleteNotification` |
| `body` | object | Notification payload (`typeWebhook`, …) |

Empty body / null `data` with HTTP 200: timeout, no notification — poll again.

**Always** delete after successful processing, or the queue will not advance.

---

## `receiving->deleteNotification`

**Docs:** https://green-api.com/en/docs/api/receiving/technology-http-api/DeleteNotification/

```php
public function deleteNotification(string $receiptId): stdClass
```

Cast integer `receiptId` to string when calling.

Notifications stored up to **24 hours**, FIFO.

---

## `receiving->downloadFile`

**Docs:** https://green-api.com/en/docs/api/receiving/files/DownloadFile/

```php
public function downloadFile(string $chatId, string $idMessage): stdClass
```

Download links for media typically valid limited time (docs: storage ~24 hours for download capability).

---

## Manual polling example

```php
while (true) {
    $result = $greenApi->receiving->receiveNotification();
    if ($result->code === 200 && !empty($result->data)) {
        $typeWebhook = $result->data->body->typeWebhook;
        $body = $result->data->body;

        if ($typeWebhook === 'incomingMessageReceived') {
            $chatId = $body->senderData->chatId ?? null;
            $text = $body->messageData->textMessageData->textMessage ?? null;
            // business logic...
        }

        $greenApi->receiving->deleteNotification((string) $result->data->receiptId);
        continue;
    }
    sleep(1);
}
```

---

## `webhooks->startReceivingNotifications` / `stopReceivingNotifications`

SDK-only helpers (not separate REST methods). Implementation in `Webhooks.php`:

- Loops while started
- Calls `receiveNotification`
- Invokes `$onEvent($typeWebhook, $body)` when data present
- Calls `deleteNotification` after callback
- Sleeps 1 s on non-200

```php
$greenApi->webhooks->startReceivingNotifications(function ($typeWebhook, $body) use ($greenApi) {
    switch ($typeWebhook) {
        case 'incomingMessageReceived':
            // handle
            break;
        case 'outgoingMessageStatus':
            // $body->status, $body->idMessage
            break;
        case 'stateInstanceChanged':
            // $body->stateInstance
            break;
    }
    // Optional: $greenApi->webhooks->stopReceivingNotifications();
});
```

**Blocking** call — runs until `stopReceivingNotifications()`.

Callback signature expected by SDK: `function ($typeWebhook, $body)`.

---

## Webhook endpoint mode

**Docs:** https://green-api.com/en/docs/api/receiving/technology-webhook-endpoint/

1. Publish public HTTPS endpoint
2. Set settings:

```php
$greenApi->account->setSettings([
    'webhookUrl' => 'https://your.domain/webhook/green-api',
    'webhookUrlToken' => '', // or Basic/Bearer token string per docs
    'incomingWebhook' => 'yes',
    'outgoingWebhook' => 'yes',
    'outgoingMessageWebhook' => 'yes',
    'outgoingAPIMessageWebhook' => 'yes',
    'stateWebhook' => 'yes',
    'incomingCallWebhook' => 'yes',
]);
```

3. Handler must accept POST JSON and respond quickly (2xx).

```php
$payload = json_decode(file_get_contents('php://input'));
// $payload->typeWebhook
header('Content-Type: text/plain');
echo 'ok';
```

Delivery retries ~1 minute; guaranteed up to 24 hours (docs).

---

## `typeWebhook` values

**Docs:** https://green-api.com/en/docs/api/receiving/notifications-format/type-webhook/

| typeWebhook | Meaning |
|-------------|---------|
| `incomingMessageReceived` | Incoming message/file |
| `outgoingMessageReceived` | Sent from phone |
| `outgoingAPIMessageReceived` | Sent via API |
| `outgoingMessageStatus` | sent/delivered/read/… |
| `stateInstanceChanged` | Auth state |
| `statusInstanceChanged` | Socket status |
| `deviceInfo` | Device/battery (archive/temp issues) |
| `incomingCall` / `outgoingCall` | Calls |
| `incomingBlock` | Block list |
| `quotaExceeded` | Developer plan limits |

Message body formats: https://green-api.com/en/docs/api/receiving/notifications-format/

Enable types via [SetSettings](https://green-api.com/en/docs/api/account/SetSettings/) (`incomingWebhook`, `outgoingWebhook`, …).

---

## Incoming text message (shape)

Example from ReceiveNotification docs:

```json
{
  "typeWebhook": "incomingMessageReceived",
  "idMessage": "...",
  "timestamp": 1588091580,
  "senderData": {
    "chatId": "79001234567@c.us",
    "sender": "79001234567@c.us",
    "senderName": "John"
  },
  "messageData": {
    "typeMessage": "textMessage",
    "textMessageData": {
      "textMessage": "Hello"
    }
  }
}
```

Always null-check nested fields — media/location/contact use other `typeMessage` branches.
