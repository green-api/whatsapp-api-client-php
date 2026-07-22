# Sending — PHP SDK (`$greenApi->sending`)

Source: `src/tools/Sending.php`  
Docs index: https://green-api.com/en/docs/api/sending/

All send methods enqueue messages. Delivery rate is controlled by instance setting `delaySendMessagesMilliseconds` ([docs](https://green-api.com/en/docs/api/send-messages-delay/)). Queue retention: up to 24 hours.

SDK response wrapper: `$result->code`, `$result->data`, `$result->error`.

---

## `sendMessage`

**Docs:** https://green-api.com/en/docs/api/sending/SendMessage/

```php
public function sendMessage(
    string $chatId,
    string $message,
    string $quotedMessageId = null,
    bool $archiveChat = false,
    int $typingTime = null
): stdClass
```

| Param (SDK) | Required | Docs notes |
|-------------|----------|------------|
| `chatId` | yes | [Chat Id](https://green-api.com/en/docs/api/chat-id/) |
| `message` | yes | Max **20000** chars; UTF-8; emoji supported |
| `quotedMessageId` | no | Same chat only; message must exist in system |
| `archiveChat` | no | Passed by SDK when true (legacy/extra field) |
| `typingTime` | no | Docs: **1000–20000** ms typing indicator |

**Not sent by this SDK** (exist in docs only): `linkPreview`, `typePreview`, `customPreview`.

**Success `data`:** `{ "idMessage": "..." }`

```php
$r = $greenApi->sending->sendMessage('79876543210@c.us', 'Hello');
// $r->data->idMessage
```

---

## `sendFileByUrl`

**Docs:** https://green-api.com/en/docs/api/sending/SendFileByUrl/

```php
public function sendFileByUrl(
    string $chatId,
    string $urlFile,
    string $fileName = null,
    string $caption = null,
    string $quotedMessageId = null,
    bool $archiveChat = false,
    int $typingTime = null,
    string $typingType = null
): stdClass
```

| Param | Required | Docs notes |
|-------|----------|------------|
| `chatId` | yes | Chat Id |
| `urlFile` | yes | Direct file URL (`http`/`https`) |
| `fileName` | API yes; SDK default = `basename($urlFile)` | Must include extension |
| `caption` | no | Max **1024** chars |
| `quotedMessageId` | no | Same chat |
| `typingTime` | no | 1000–20000 ms |
| `typingType` | no | `recording` for audio recording indicator |
| `archiveChat` | no | SDK optional |

**Limits (docs):** max file **100 MB**; one file per request.

**Success `data`:** `{ "idMessage": "..." }`

---

## `sendFileByUpload`

**Docs:** https://green-api.com/en/docs/api/sending/SendFileByUpload/  
Uses **media** host (`media.green-api.com` by default).

```php
public function sendFileByUpload(
    string $chatId,
    string $path,
    string $fileName = null,
    string $caption = null,
    string $quotedMessageId = null,
    int $typingTime = null,
    string $typingType = null
): stdClass
```

| Param | Required | Docs notes |
|-------|----------|------------|
| `chatId` | yes | Chat Id |
| `path` | yes (SDK local path) | Maps to form field `file` |
| `fileName` | no | Default basename of path; extension required |
| `caption` | no | Max 1024 |
| `quotedMessageId` | no | |
| `typingTime` / `typingType` | no | As above |

**Success `data`:** `{ "idMessage": "...", "urlFile": "..." }` — `urlFile` valid ~**15 days**.

---

## `uploadFile`

**Docs:** https://green-api.com/en/docs/api/sending/UploadFile/  
Media host; binary body. Link lifetime **15 days**; max **100 MB**.

```php
public function uploadFile(string $path): stdClass
```

**Success `data`:** `{ "urlFile": "..." }`

Typical flow: `uploadFile` → `sendFileByUrl` with returned URL.

---

## `sendLocation`

**Docs:** https://green-api.com/en/docs/api/sending/SendLocation/

```php
public function sendLocation(
    string $chatId,
    float $latitude,
    float $longitude,
    string $nameLocation = null,
    string $address = null,
    string $quotedMessageId = null,
    int $typingTime = null
): stdClass
```

**Success `data`:** `{ "idMessage": "..." }`

---

## `sendContact`

**Docs:** https://green-api.com/en/docs/api/sending/SendContact/

```php
public function sendContact(
    string $chatId,
    array $contact,
    string $quotedMessageId = null,
    int $typingTime = null
): stdClass
```

`contact` object (docs):

| Field | Required | Notes |
|-------|----------|-------|
| `phoneContact` | yes | International digits, no `+`, 11–16 digits |
| `firstName` / `middleName` / `lastName` / `company` | at least one name-like field | Conditional per docs |

```php
$greenApi->sending->sendContact('79876543210@c.us', [
    'phoneContact' => 79001234567,
    'firstName' => 'Artem',
    'lastName' => 'Example',
    'company' => 'Acme',
]);
```

---

## `forwardMessages`

**Docs:** https://green-api.com/en/docs/api/sending/ForwardMessages/

```php
public function forwardMessages(
    string $chatId,
    string $chatIdFrom,
    array $messages,
    int $typingTime = null
): stdClass
```

| Param | Required | Notes |
|-------|----------|-------|
| `chatId` | yes | Destination |
| `chatIdFrom` | yes | Source chat |
| `messages` | yes | Array of message IDs (min 1) |
| `typingTime` | no | 1000–20000 |

Messages must already be known to the system (incoming/outgoing webhooks enabled as needed).

**Success `data`:** `{ "messages": ["id1", "id2"] }`

---

## `sendLink`

**Docs:** https://green-api.com/en/docs/api/sending/SendLink/

```php
public function sendLink(
    string $chatId,
    string $urlLink,
    string $quotedMessageId = null
): stdClass
```

Preview from Open Graph of the URL.

---

## Archived button methods (still in SDK)

Prefer modern interactive APIs in **docs** only if you call raw HTTP; this PHP SDK still implements:

### `sendButtons`

**Docs:** https://green-api.com/en/docs/api/sending/SendButtons/

```php
public function sendButtons(
    string $chatId,
    string $message,
    string $footer,
    array $buttons,
    string $quotedMessageId = null,
    bool $archiveChat = false
): stdClass
```

### `sendTemplateButtons`

**Docs:** https://green-api.com/en/docs/api/sending/SendTemplateButtons/

```php
public function sendTemplateButtons(
    string $chatId,
    string $message,
    array $templateButtons,
    string $footer = null,
    string $quotedMessageId = null,
    bool $archiveChat = false
): stdClass
```

### `sendListMessage`

**Docs:** https://green-api.com/en/docs/api/sending/SendListMessage/

```php
public function sendListMessage(
    string $chatId,
    string $message,
    array $sections,
    string $title = null,
    string $footer = null,
    string $buttonText = null,
    string $quotedMessageId = null,
    bool $archiveChat = false
): stdClass
```

For button/list field structures, follow the official docs pages above.

---

## Not implemented in this SDK

Do **not** call via `$greenApi->sending`: `sendPoll`, `sendInteractiveButtons`, `sendInteractiveButtonsReply`.
