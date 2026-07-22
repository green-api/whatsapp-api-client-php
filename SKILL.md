---
name: green-api-whatsapp-php-sdk
version: "1.0.0"
description: >
  Teach AI agents to write correct PHP code with green-api/whatsapp-api-client-php
  (GREEN-API WhatsApp HTTP API). Use when integrating WhatsApp via GREEN-API in PHP:
  sendMessage, sendFileByUrl, sendFileByUpload, receiveNotification, webhooks,
  groups, account, statuses, contacts, or composer package green-api/whatsapp-api-client-php.
  Sources of truth: official docs https://green-api.com/en/docs/api/ and this repo's src/.
---

# GREEN-API WhatsApp PHP SDK — Agent Skill

## Purpose

Write **working PHP** against package `green-api/whatsapp-api-client-php` only with methods that exist in `src/`. Semantics, parameters, formats, and limits come from **official docs**: [green-api.com/en/docs/api/](https://green-api.com/en/docs/api/).

**Do not invent methods.** If a method is in the docs but not in this SDK (for example `sendPoll`, `sendInteractiveButtons`, `getChats`, `getStatusInstance`), do not call it via this client.

---

## When to apply

- PHP + GREEN-API / WhatsApp HTTP API
- `composer require green-api/whatsapp-api-client-php`
- Send text/files, polling notifications, webhooks, groups, account state
- Agent must produce code that runs without manual API-name fixes

---

## Sources of truth (mandatory)

| Source | Use for |
|--------|---------|
| [Official API docs](https://green-api.com/en/docs/api/) | Purpose, required/optional params, response fields, chatId, delays, auth, webhooks |
| This repository `src/` | Class names, property names (`$greenApi->sending`), method signatures, what is implemented |

If docs and SDK differ on **call shape**, follow the **SDK signature**. If docs and SDK differ on **HTTP semantics**, follow the **docs** (and only pass fields the SDK actually sends).

---

## Installation

```bash
composer require green-api/whatsapp-api-client-php
```

Requirements (from `composer.json`): PHP `>=7.0`, extensions `curl`, `json`, `fileinfo`.

```php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;
```

---

## Client initialization (from `src/GreenApiClient.php`)

```php
// Instance methods (account, sending, receiving, …)
$greenApi = new GreenApiClient($idInstance, $apiTokenInstance);

// Optional custom hosts (4th and 5th args). 3rd arg is partnerToken, NOT host.
$greenApi = new GreenApiClient(
    $idInstance,
    $apiTokenInstance,
    null,                              // partnerToken
    'https://api.green-api.com',       // host (apiUrl)
    'https://media.green-api.com'      // media (mediaUrl)
);

// Partner API only (no idInstance/apiTokenInstance required for partner methods)
$partnerApi = new GreenApiClient(null, null, $partnerToken);
```

Credentials: [console.green-api.com](https://console.green-api.com) — `idInstance`, `apiTokenInstance`, and optionally `apiUrl` / `mediaUrl` from the instance card ([Before you start](https://green-api.com/en/docs/before-start/)).

Prefer env vars (never hardcode secrets in committed code):

```php
$idInstance = getenv('ID_INSTANCE');
$apiTokenInstance = getenv('API_TOKEN_INSTANCE');
$greenApi = new GreenApiClient($idInstance, $apiTokenInstance);
```

### Public modules on the client

| Property | Class | File |
|----------|--------|------|
| `$greenApi->account` | `Account` | `src/tools/Account.php` |
| `$greenApi->contacts` | `Contacts` | `src/tools/Contacts.php` |
| `$greenApi->sending` | `Sending` | `src/tools/Sending.php` |
| `$greenApi->groups` | `Groups` | `src/tools/Groups.php` |
| `$greenApi->journals` | `Journals` | `src/tools/Journals.php` |
| `$greenApi->marking` | `Marking` | `src/tools/Marking.php` |
| `$greenApi->queues` | `Queues` | `src/tools/Queues.php` |
| `$greenApi->receiving` | `Receiving` | `src/tools/Receiving.php` |
| `$greenApi->serviceMethods` | `ServiceMethods` | `src/tools/ServiceMethods.php` |
| `$greenApi->webhooks` | `Webhooks` | `src/tools/Webhooks.php` |
| `$greenApi->statuses` | `Statuses` | `src/tools/Statuses.php` |
| `$greenApi->partner` | `Partner` | `src/tools/Partner.php` (only if `partnerToken` set) |

---

## Response shape (SDK-specific)

Every API call returns `stdClass`:

```php
$result->code;   // HTTP status (200 = success path in SDK)
$result->data;   // decoded JSON body on success, or null
$result->error;  // error body/string on failure, or null
// On transport failure: code = 0, error = curl message
```

Typical success check:

```php
if ($result->code === 200 && $result->data !== null) {
    // use $result->data
} else {
    // inspect $result->error / $result->code
}
```

---

## Critical rules (docs)

### 1. `chatId` formats

From [Chat Id](https://green-api.com/en/docs/api/chat-id/):

| Type | Format | Example |
|------|--------|---------|
| Personal | `{phone}@c.us` | `79876543210@c.us` |
| Group | `{id}@g.us` (system-generated) | `120363043968066561@g.us` |
| Lid | `{id}@lid` | `120650379300963@lid` |

Phone: full international number, **no** `+`, spaces, or dashes. **Do not invent group IDs** — take them from API responses.

### 2. Instance must be authorized

From [GetStateInstance](https://green-api.com/en/docs/api/account/GetStateInstance/) and [Before you start](https://green-api.com/en/docs/before-start/):

```php
$state = $greenApi->account->getStateInstance();
// $state->data->stateInstance === 'authorized'
// notAuthorized | blocked | sleepMode | starting | suspended | ...
```

Authorize via console QR (or `account->qr()` / `getAuthorizationCode`). Phone must stay online/charged.

### 3. Message sending delay

From [Messages sending delay](https://green-api.com/en/docs/api/send-messages-delay/):

- Outgoing messages go through a **FIFO queue**
- Server delay: `delaySendMessagesMilliseconds` via `account->setSettings([...])`
- Min **500 ms**, max **600000 ms**, recommended ≤ **300000 ms**
- Messages stay in queue up to **24 hours** until authorized/sent

```php
$greenApi->account->setSettings([
    'delaySendMessagesMilliseconds' => 1000,
]);
```

### 4. HTTP API vs webhook receiving

- **Polling:** `webhookUrl` must be **empty**. Use `receiving->receiveNotification` + `deleteNotification`, or helper `webhooks->startReceivingNotifications`.
- **Webhook endpoint:** set `webhookUrl` via `setSettings` / console. Polling then fails with an error that a custom webhook URL is set ([ReceiveNotification](https://green-api.com/en/docs/api/receiving/technology-http-api/ReceiveNotification/)).

---

## Quick scenarios (copy-paste patterns)

### Send text

Docs: [SendMessage](https://green-api.com/en/docs/api/sending/SendMessage/)  
SDK: `Sending::sendMessage`

```php
<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

$greenApi = new GreenApiClient(getenv('ID_INSTANCE'), getenv('API_TOKEN_INSTANCE'));

// Optional: confirm authorization
$state = $greenApi->account->getStateInstance();
if ($state->code !== 200 || ($state->data->stateInstance ?? null) !== 'authorized') {
    fwrite(STDERR, "Instance not authorized\n");
    exit(1);
}

$chatId = '79876543210@c.us'; // full phone + @c.us
$result = $greenApi->sending->sendMessage($chatId, 'Hello from GREEN-API PHP SDK');

if ($result->code === 200) {
    echo $result->data->idMessage, PHP_EOL;
} else {
    print_r($result->error);
}
```

Optional SDK args: `quotedMessageId`, `archiveChat`, `typingTime` (docs: `typingTime` 1000–20000 ms).  
Message max length (docs): **20000** characters.

### Send file by URL

Docs: [SendFileByUrl](https://green-api.com/en/docs/api/sending/SendFileByUrl/) — max file size **100 MB**; `fileName` with extension required by API (SDK fills basename of URL if omitted).

```php
$result = $greenApi->sending->sendFileByUrl(
    '79876543210@c.us',
    'https://www.google.ru/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
    'googlelogo_color_272x92dp.png',
    'Google logo'
);
// $result->data->idMessage
```

### Send file by upload (disk)

Docs: [SendFileByUpload](https://green-api.com/en/docs/api/sending/SendFileByUpload/) — uses **media** host; form-data.

```php
$result = $greenApi->sending->sendFileByUpload(
    '79876543210@c.us',
    '/path/to/file.png',
    'file.png',
    'Caption'
);
// $result->data->idMessage, $result->data->urlFile
```

### Upload then send by URL

Docs: [UploadFile](https://green-api.com/en/docs/api/sending/UploadFile/) — link lifetime **15 days**.

```php
$upload = $greenApi->sending->uploadFile('/path/to/file.png');
if ($upload->code === 200) {
    $send = $greenApi->sending->sendFileByUrl(
        '79876543210@c.us',
        $upload->data->urlFile,
        'file.png',
        'Caption'
    );
}
```

### Polling notifications (manual loop)

Docs: [ReceiveNotification](https://green-api.com/en/docs/api/receiving/technology-http-api/ReceiveNotification/), [DeleteNotification](https://green-api.com/en/docs/api/receiving/technology-http-api/DeleteNotification/).  
SDK does **not** pass `receiveTimeout` (API default applies).

```php
while (true) {
    $result = $greenApi->receiving->receiveNotification();
    if ($result->code === 200 && !empty($result->data)) {
        $receiptId = $result->data->receiptId;
        $body = $result->data->body;
        $typeWebhook = $body->typeWebhook;

        // process $typeWebhook / $body ...

        $greenApi->receiving->deleteNotification((string) $receiptId);
        continue;
    }
    sleep(1);
}
```

### Polling via helper

SDK: `Webhooks::startReceivingNotifications` (blocking loop; auto-deletes after callback).

```php
$greenApi->webhooks->startReceivingNotifications(function ($typeWebhook, $body) {
    if ($typeWebhook === 'incomingMessageReceived') {
        // $body->messageData, $body->senderData, ...
    }
    // To stop from another context: $greenApi->webhooks->stopReceivingNotifications();
});
```

### Webhook HTTP endpoint (your server)

Docs: [Webhook Endpoint](https://green-api.com/en/docs/api/receiving/technology-webhook-endpoint/).  
Configure `webhookUrl` + types via `account->setSettings` or console. GREEN-API POSTs JSON; respond 200 quickly.

```php
// After public HTTPS endpoint exists:
$greenApi->account->setSettings([
    'webhookUrl' => 'https://example.com/green-api/webhook',
    'incomingWebhook' => 'yes',
    'outgoingWebhook' => 'yes',
    'outgoingAPIMessageWebhook' => 'yes',
    'stateWebhook' => 'yes',
]);

// webhook.php
$payload = json_decode(file_get_contents('php://input'));
// $payload->typeWebhook — see notifications format
http_response_code(200);
echo 'ok';
```

Common `typeWebhook` values ([docs](https://green-api.com/en/docs/api/receiving/notifications-format/type-webhook/)):  
`incomingMessageReceived`, `outgoingMessageReceived`, `outgoingAPIMessageReceived`, `outgoingMessageStatus`, `stateInstanceChanged`, `statusInstanceChanged`, `incomingCall`, `outgoingCall`, `quotaExceeded`, …

### Create group and send message

Docs: [CreateGroup](https://green-api.com/en/docs/api/groups/CreateGroup/) — ≤ **1 group / 5 minutes**; only real WhatsApp numbers.

```php
$create = $greenApi->groups->createGroup('My group', [
    '79876543210@c.us',
]);
if ($create->code === 200 && !empty($create->data->created)) {
    $greenApi->sending->sendMessage($create->data->chatId, 'Welcome');
}
```

### Typing indicator

Docs: [SendTyping](https://green-api.com/en/docs/api/service/SendTyping/)

```php
$greenApi->serviceMethods->sendTyping('79876543210@c.us', 5000);
$greenApi->serviceMethods->sendTyping('79876543210@c.us', 5000, 'recording');
```

---

## Method inventory (only SDK methods)

Detail pages: `references/*.md`. Doc links are official.

### `account` — [Account](https://green-api.com/en/docs/api/account/)

| SDK method | Docs |
|------------|------|
| `getSettings()` | [GetSettings](https://green-api.com/en/docs/api/account/GetSettings/) |
| `setSettings(array $requestBody)` | [SetSettings](https://green-api.com/en/docs/api/account/SetSettings/) |
| `getStateInstance()` | [GetStateInstance](https://green-api.com/en/docs/api/account/GetStateInstance/) |
| `reboot()` | [Reboot](https://green-api.com/en/docs/api/account/Reboot/) |
| `logout()` | [Logout](https://green-api.com/en/docs/api/account/Logout/) |
| `qr()` | [QR](https://green-api.com/en/docs/api/account/QR/) |
| `getAuthorizationCode(int $phoneNumber)` | [GetAuthorizationCode](https://green-api.com/en/docs/api/account/GetAuthorizationCode/) |
| `setProfilePicture(string $path)` | [SetProfilePicture](https://green-api.com/en/docs/api/account/SetProfilePicture/) |
| `getWaSettings()` | [GetWaSettings](https://green-api.com/en/docs/api/account/GetWaSettings/) |

### `sending` — [Sending](https://green-api.com/en/docs/api/sending/)

| SDK method | Docs |
|------------|------|
| `sendMessage(...)` | [SendMessage](https://green-api.com/en/docs/api/sending/SendMessage/) |
| `sendFileByUrl(...)` | [SendFileByUrl](https://green-api.com/en/docs/api/sending/SendFileByUrl/) |
| `sendFileByUpload(...)` | [SendFileByUpload](https://green-api.com/en/docs/api/sending/SendFileByUpload/) |
| `uploadFile(string $path)` | [UploadFile](https://green-api.com/en/docs/api/sending/UploadFile/) |
| `sendLocation(...)` | [SendLocation](https://green-api.com/en/docs/api/sending/SendLocation/) |
| `sendContact(...)` | [SendContact](https://green-api.com/en/docs/api/sending/SendContact/) |
| `forwardMessages(...)` | [ForwardMessages](https://green-api.com/en/docs/api/sending/ForwardMessages/) |
| `sendLink(...)` | [SendLink](https://green-api.com/en/docs/api/sending/SendLink/) |
| `sendButtons(...)` | [SendButtons](https://green-api.com/en/docs/api/sending/SendButtons/) (archive) |
| `sendTemplateButtons(...)` | [SendTemplateButtons](https://green-api.com/en/docs/api/sending/SendTemplateButtons/) (archive) |
| `sendListMessage(...)` | [SendListMessage](https://green-api.com/en/docs/api/sending/SendListMessage/) (archive) |

### `receiving` / `webhooks`

| SDK method | Docs |
|------------|------|
| `receiving->receiveNotification()` | [ReceiveNotification](https://green-api.com/en/docs/api/receiving/technology-http-api/ReceiveNotification/) |
| `receiving->deleteNotification(string $receiptId)` | [DeleteNotification](https://green-api.com/en/docs/api/receiving/technology-http-api/DeleteNotification/) |
| `receiving->downloadFile(string $chatId, string $idMessage)` | [DownloadFile](https://green-api.com/en/docs/api/receiving/files/DownloadFile/) |
| `webhooks->startReceivingNotifications($onEvent)` | SDK helper over receive+delete |
| `webhooks->stopReceivingNotifications()` | SDK helper |

### `groups` — [Groups](https://green-api.com/en/docs/api/groups/)

`createGroup`, `updateGroupName`, `getGroupData`, `addGroupParticipant`, `removeGroupParticipant`, `setGroupAdmin`, `removeAdmin`, `setGroupPicture`, `leaveGroup` — see [references/groups.md](./references/groups.md).

### `journals` — [Journals](https://green-api.com/en/docs/api/journals/)

`getChatHistory`, `getMessage`, `lastIncomingMessages`, `lastOutgoingMessages` — [references/journals.md](./references/journals.md).

### `queues` / `marking`

`queues->showMessagesQueue`, `queues->clearMessagesQueue` — [Queues](https://green-api.com/en/docs/api/queues/)  
`marking->readChat(string $chatId, string $idMessage)` — [ReadChat](https://green-api.com/en/docs/api/marks/ReadChat/) (SDK requires `idMessage`; docs allow omit for “all”).

### `serviceMethods` — [Service](https://green-api.com/en/docs/api/service/)

`checkWhatsapp`, `getAvatar`, `getContacts`, `getContactInfo`, `editMessage`, `deleteMessage`, `archiveChat`, `unarchiveChat`, `setDisappearingChat`, `sendTyping` — [references/service.md](./references/service.md).

Note: SDK `checkWhatsapp(int $phoneNumber)` only (docs also allow `chatId` / `force` — **not** in this SDK).

### `contacts` — [Contacts](https://green-api.com/en/docs/api/contacts/)

`addContact`, `editContact`, `deleteContact` — [references/contacts.md](./references/contacts.md).

### `statuses` — [Statuses](https://green-api.com/en/docs/api/statuses/)

`sendTextStatus`, `sendVoiceStatus`, `sendMediaStatus`, `getIncomingStatuses`, `getOutgoingStatuses`, `getStatusStatistic` — [references/statuses.md](./references/statuses.md).

### `partner` — [Partners](https://green-api.com/en/docs/partners/)

`getInstances`, `createInstance($payload)`, `deleteInstanceAccount($idInstance)` — [references/partner.md](./references/partner.md).

---

## Not in this SDK (do not invent)

Examples present in official docs / other language SDKs but **missing** from this PHP `src/`:

- `sendPoll`, `sendInteractiveButtons`, `sendInteractiveButtonsReply`
- `getChats`, `getStatusInstance`, `getStateInstanceHistory`, `updateApiToken`
- `updateGroupSettings`, `deleteStatus`
- `lastIncomingCalls`, `lastOutgoingCalls`
- `getWebhooksCount`, `clearWebhooksQueue`
- ReceiveNotification `receiveTimeout` query parameter

Use raw HTTP only if the user explicitly asks to call the REST API outside the SDK.

---

## Pitfalls

| Issue | Fix |
|-------|-----|
| Missing `@c.us` / `@g.us` | Use full chatId from docs |
| `notAuthorized` | Scan QR / `getAuthorizationCode`; recheck `getStateInstance` |
| Messages delayed | Normal: queue + `delaySendMessagesMilliseconds` |
| Polling returns webhook URL error | Clear `webhookUrl` for HTTP API receive mode |
| Forgot `deleteNotification` | Queue stalls; always delete after process |
| Create group with non-WhatsApp numbers | Risk of ban / errors — check with `checkWhatsapp` first |
| Constructor 3rd arg as host | 3rd is `partnerToken`; hosts are 4th/5th |
| Wrong property name | Use `serviceMethods` not `service`; `marking` not `marks` |
| `code === 200` but empty `data` on receive | No notification (timeout) — loop again |
| File send fails | Use media host (SDK does for upload methods); size ≤ 100 MB |

---

## Agent checklist before finishing code

1. `use GreenApi\RestApi\GreenApiClient;` + `vendor/autoload.php`
2. Credentials from env / config, not fake tokens in production samples
3. `chatId` with correct suffix
4. Methods/properties match this inventory (grep `src/` if unsure)
5. Check `$result->code` and `$result->error`
6. Receiving: delete notification after handling
7. Do not claim support for methods not listed above

---

## References (details)

- [references/sending.md](./references/sending.md)
- [references/receiving.md](./references/receiving.md)
- [references/account.md](./references/account.md)
- [references/groups.md](./references/groups.md)
- [references/service.md](./references/service.md)
- [references/journals.md](./references/journals.md)
- [references/queues-marking.md](./references/queues-marking.md)
- [references/contacts.md](./references/contacts.md)
- [references/statuses.md](./references/statuses.md)
- [references/partner.md](./references/partner.md)

**Official docs:** https://green-api.com/en/docs/api/  
**Packagist:** https://packagist.org/packages/green-api/whatsapp-api-client-php  
**Console:** https://console.green-api.com/
