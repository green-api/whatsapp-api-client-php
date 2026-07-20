# Journals — PHP SDK (`$greenApi->journals`)

Source: `src/tools/Journals.php`  
Docs: https://green-api.com/en/docs/api/journals/

---

## `getChatHistory`

**Docs:** https://green-api.com/en/docs/api/journals/GetChatHistory/

```php
public function getChatHistory(string $chatId, int $count = null): stdClass
```

| Param | Required | Notes |
|-------|----------|-------|
| `chatId` | yes | Chat Id |
| `count` | no | Number of messages (see docs for limits) |

---

## `getMessage`

**Docs:** https://green-api.com/en/docs/api/journals/GetMessage/

```php
public function getMessage(string $chatId, string $idMessage): stdClass
```

Useful before quote/forward — message must exist in the system.

---

## `lastIncomingMessages`

**Docs:** https://green-api.com/en/docs/api/journals/LastIncomingMessages/

```php
public function lastIncomingMessages(int $minutes = null): stdClass
```

Optional `minutes` window. Default mode returns recent journal (see docs; typically last 24h context for related features).

---

## `lastOutgoingMessages`

**Docs:** https://green-api.com/en/docs/api/journals/LastOutgoingMessages/

```php
public function lastOutgoingMessages(int $minutes = null): stdClass
```

Docs: outgoing messages stored on server for **24 hours**.

---

## Not in this SDK

- `lastIncomingCalls`, `lastOutgoingCalls`
