# Account — PHP SDK (`$greenApi->account`)

Source: `src/tools/Account.php`  
Docs: https://green-api.com/en/docs/api/account/

---

## `getSettings`

**Docs:** https://green-api.com/en/docs/api/account/GetSettings/

```php
public function getSettings(): stdClass
```

Returns current instance settings (webhook URL, delays, webhook type switches, etc.).

---

## `setSettings`

**Docs:** https://green-api.com/en/docs/api/account/SetSettings/

```php
public function setSettings(array $requestBody): stdClass
```

Partial update allowed; at least one field. **Instance reboots**; settings apply within ~5 minutes (docs).

Important fields (docs):

| Field | Values / notes |
|-------|----------------|
| `webhookUrl` | Notification URL; empty string for HTTP API polling |
| `webhookUrlToken` | Auth token for your webhook server |
| `delaySendMessagesMilliseconds` | 500–600000; recommended ≤ 300000 |
| `incomingWebhook` | `yes` / `no` |
| `outgoingWebhook` | `yes` / `no` (statuses) |
| `outgoingMessageWebhook` | phone-sent messages |
| `outgoingAPIMessageWebhook` | API-sent messages |
| `stateWebhook` | instance auth state |
| `incomingCallWebhook` | calls |
| `editedMessageWebhook` / `deletedMessageWebhook` | edit/delete |
| `keepOnlineStatus` | keep Online |
| `markIncomingMessagesReaded` | `yes` / `no` |
| `markIncomingMessagesReadedOnReply` | `yes` / `no` |
| `autoTyping` | 0–10 |
| `linkPreview` | `yes` / `no` |
| `enableLidMode` | `yes` / `no` for `@lid` |

**Success `data`:** `{ "saveSettings": true }`

```php
$greenApi->account->setSettings([
    'delaySendMessagesMilliseconds' => 1000,
    'incomingWebhook' => 'yes',
    'webhookUrl' => '',
]);
```

---

## `getStateInstance`

**Docs:** https://green-api.com/en/docs/api/account/GetStateInstance/

```php
public function getStateInstance(): stdClass
```

**Success `data`:**

| `stateInstance` | Meaning |
|-----------------|---------|
| `notAuthorized` | Scan QR / authorize |
| `authorized` | Ready |
| `blocked` | Banned |
| `sleepMode` | Outdated; phone off possible |
| `starting` | Starting/maintenance |
| `suspended` | Temporary restrictions |
| `yellowCard` | Deprecated → use suspended |

Always check before production sends.

---

## `reboot` / `logout`

**Docs:** [Reboot](https://green-api.com/en/docs/api/account/Reboot/), [Logout](https://green-api.com/en/docs/api/account/Logout/)

```php
public function reboot(): stdClass
public function logout(): stdClass
```

`logout` disconnects WhatsApp account from instance.

---

## `qr`

**Docs:** https://green-api.com/en/docs/api/account/QR/

```php
public function qr(): stdClass
```

QR for linking WhatsApp / WhatsApp Business ([Before you start](https://green-api.com/en/docs/before-start/)).

---

## `getAuthorizationCode`

**Docs:** https://green-api.com/en/docs/api/account/GetAuthorizationCode/

```php
public function getAuthorizationCode(int $phoneNumber): stdClass
```

Authorize by phone number (linking code flow). Phone as integer international digits.

---

## `setProfilePicture`

**Docs:** https://green-api.com/en/docs/api/account/SetProfilePicture/

```php
public function setProfilePicture(string $path): stdClass
```

Local image path; SDK sets MIME `image/jpeg` on upload.

---

## `getWaSettings`

**Docs:** https://green-api.com/en/docs/api/account/GetWaSettings/

```php
public function getWaSettings(): stdClass
```

WhatsApp account information for the instance.

---

## Not in this SDK

- `getStatusInstance` (docs archive; **not** in `Account.php`)
- `getStateInstanceHistory`, `Scanqrcode`, `updateApiToken`
