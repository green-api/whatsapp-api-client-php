# Groups — PHP SDK (`$greenApi->groups`)

Source: `src/tools/Groups.php`  
Docs: https://green-api.com/en/docs/api/groups/

Group chatId format: `...@g.us` ([Chat Id](https://green-api.com/en/docs/api/chat-id/)). Never invent IDs — use API responses.

---

## `createGroup`

**Docs:** https://green-api.com/en/docs/api/groups/CreateGroup/

```php
public function createGroup(string $groupName, array $chatIds): stdClass
```

| Param | Required | Docs |
|-------|----------|------|
| `groupName` | yes | Max **100** characters |
| `chatIds` | yes | Array of participant chatIds (`phone@c.us`) |

**Rate (docs):** no more than **1 group every 5 minutes**. Simulate human pacing.

**Success `data`:**

| Field | Description |
|-------|-------------|
| `created` | boolean |
| `chatId` | group id `@g.us` |
| `groupInviteLink` | invite URL |

**Risks:** non-WhatsApp numbers in `chatIds` → errors / ban risk. Prefer `serviceMethods->checkWhatsapp` first.

---

## `updateGroupName`

**Docs:** https://green-api.com/en/docs/api/groups/UpdateGroupName/

```php
public function updateGroupName(string $groupId, string $groupName): stdClass
```

---

## `getGroupData`

**Docs:** https://green-api.com/en/docs/api/groups/GetGroupData/

```php
public function getGroupData(string $groupId): stdClass
```

Returns group metadata and participants (see docs for full schema).

---

## `addGroupParticipant` / `removeGroupParticipant`

**Docs:** [Add](https://green-api.com/en/docs/api/groups/AddGroupParticipant/), [Remove](https://green-api.com/en/docs/api/groups/RemoveGroupParticipant/)

```php
public function addGroupParticipant(string $groupId, string $participantChatId): stdClass
public function removeGroupParticipant(string $groupId, string $participantChatId): stdClass
```

---

## `setGroupAdmin` / `removeAdmin`

**Docs:** [SetGroupAdmin](https://green-api.com/en/docs/api/groups/SetGroupAdmin/), [RemoveAdmin](https://green-api.com/en/docs/api/groups/RemoveAdmin/)

```php
public function setGroupAdmin(string $groupId, string $participantChatId): stdClass
public function removeAdmin(string $groupId, string $participantChatId): stdClass
```

---

## `setGroupPicture`

**Docs:** https://green-api.com/en/docs/api/groups/SetGroupPicture/

```php
public function setGroupPicture(string $groupId, string $path): stdClass
```

Local file path; SDK uploads as JPEG form-data.

---

## `leaveGroup`

**Docs:** https://green-api.com/en/docs/api/groups/LeaveGroup/

```php
public function leaveGroup(string $groupId): stdClass
```

Current account leaves the group.

---

## Example

```php
$create = $greenApi->groups->createGroup('Team', ['79876543210@c.us']);
if ($create->code === 200 && $create->data->created) {
    $groupId = $create->data->chatId;
    $greenApi->sending->sendMessage($groupId, 'Hello group');
    $greenApi->groups->getGroupData($groupId);
}
```

---

## Not in this SDK

- `updateGroupSettings` (exists in docs, not in PHP `Groups.php`)
