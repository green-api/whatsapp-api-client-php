# Service methods — PHP SDK (`$greenApi->serviceMethods`)

Source: `src/tools/ServiceMethods.php`  
Docs: https://green-api.com/en/docs/api/service/

Property name is **`serviceMethods`**, not `service`.

---

## `checkWhatsapp`

**Docs:** https://green-api.com/en/docs/api/service/CheckWhatsapp/

```php
public function checkWhatsapp(int $phoneNumber): stdClass
```

SDK sends only `phoneNumber` (integer).  
Docs also support `chatId` and `force` — **not exposed** by this SDK.

**Success `data` (docs):** `existsWhatsapp` (bool), optionally `chatId` (e.g. `@lid`), `fromCache`.

```php
$r = $greenApi->serviceMethods->checkWhatsapp(79876543210);
// $r->data->existsWhatsapp
```

---

## `getAvatar`

**Docs:** https://green-api.com/en/docs/api/service/GetAvatar/

```php
public function getAvatar(string $chatId): stdClass
```

---

## `getContacts`

**Docs:** https://green-api.com/en/docs/api/service/GetContacts/

```php
public function getContacts(): stdClass
```

List of contacts known to the account.

---

## `getContactInfo`

**Docs:** https://green-api.com/en/docs/api/service/GetContactInfo/

```php
public function getContactInfo(string $chatId): stdClass
```

---

## `editMessage`

**Docs:** https://green-api.com/en/docs/api/service/EditMessage/

```php
public function editMessage(string $chatId, string $idMessage, string $message): stdClass
```

---

## `deleteMessage`

**Docs:** https://green-api.com/en/docs/api/service/DeleteMessage/

```php
public function deleteMessage(
    string $chatId,
    string $idMessage,
    bool $onlySenderDelete = false
): stdClass
```

---

## `archiveChat` / `unarchiveChat`

**Docs:** [ArchiveChat](https://green-api.com/en/docs/api/service/archiveChat/), [UnarchiveChat](https://green-api.com/en/docs/api/service/unarchiveChat/)

```php
public function archiveChat(string $chatId): stdClass
public function unarchiveChat(string $chatId): stdClass
```

Docs: archive only chats with at least one incoming message.

---

## `setDisappearingChat`

**Docs:** https://green-api.com/en/docs/api/service/SetDisappearingChat/

```php
public function setDisappearingChat(string $chatId, int $ephemeralExpiration): stdClass
```

Standard values (docs): `0` off, `86400` (24h), `604800` (7d), `7776000` (90d).

---

## `sendTyping`

**Docs:** https://green-api.com/en/docs/api/service/SendTyping/

```php
public function sendTyping(
    string $chatId,
    int $typingTime = null,
    string $typingType = null
): stdClass
```

| Param | Docs |
|-------|------|
| `typingTime` | 1000–20000 ms |
| `typingType` | omit for text typing; `recording` for audio |

```php
$greenApi->serviceMethods->sendTyping('79876543210@c.us', 5000);
$greenApi->serviceMethods->sendTyping('79876543210@c.us', 5000, 'recording');
```

Total perceived delay = queue delay + typingTime (docs note).

---

## Not in this SDK

- `getChats` (docs service method; not in PHP `ServiceMethods.php`)
