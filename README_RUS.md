# whatsapp-api-client-php
[![Total Downloads](https://poser.pugx.org/green-api/whatsapp-api-client-php/downloads?format=flat-square)](https://packagist.org/packages/green-api/whatsapp-api-client-php)
[![Downloads per month](https://img.shields.io/packagist/dm/green-api/whatsapp-api-client-php.svg?style=flat-square)](https://packagist.org/packages/green-api/whatsapp-api-client-php)
[![License](https://img.shields.io/badge/license-Apache%202.0-red.svg?style=flat-square)](https://packagist.org/packages/green-api/whatsapp-api-client-php)

PHP библиотека для интеграции с мессенджером WhatsApp через API сервиса [green-api.com](https://green-api.com/). Чтобы воспользоваться библиотекой, нужно получить регистрационный токен и id аккаунта в [личном кабинете](https://console.green-api.com). Есть бесплатный тариф аккаунта разработчика.

## API

Документация к REST API находится по [ссылке](https://green-api.com/docs/api/). Библиотека является оберткой к REST API, поэтому документация по ссылке выше применима и к самой библиотеке.

## Установка
Через [Composer](https://getcomposer.org):

```bash
composer require green-api/whatsapp-api-client-php
```

## Import 

```
require './vendor/autoload.php';
```
## Авторизация 

Чтобы отправить сообщение или выполнить другой метод Green-API, аккаунт WhatsApp в приложении телефона должен быть в авторизованном состоянии. Для авторизации аккаунта перейдите в [личный кабинет](https://console.green-api.com) и сканируйте QR-код с использованием приложения WhatsApp.

## Запуск index.php

```
php -S localhost:8080
```

## Примеры

### Как инициализировать объект

```
$greenApi = new GreenApiClient( idInstance: ID_INSTANCE, apiTokenInstance: API_TOKEN_INSTANCE );
```

### Отправка текстового сообщения на номер WhatsApp

```
$result = $greenApi->sending->sendMessage('11001234567@g.us', 'Message text');
```

Ссылка на пример: [sendTextMessage.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/sendTextMessage.php)

Обратите внимание, что ключи можно получать из переменных среды:
```
<?php
require './vendor/autoload.php';

define( "ID_INSTANCE", getenv("ID_INSTANCE" ));
define( "API_TOKEN_INSTANCE", getenv("API_TOKEN_INSTANCE") );
```

### Отправка картинки по URL

```
$result = $greenApi->sending->sendFileByUrl(
        '11001234567@c.us', 'https://www.google.ru/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
        'googlelogo_color_272x92dp.png', 'Google logo');
```

Ссылка на пример: [sendPictureByLink.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/sendPictureByLink.php)

### Отправка картинки загрузкой с диска

```
result = greenAPI.sending.sendFileByUpload('120363025955348359@g.us', 
        'C:\Games\PicFromDisk.png', 
        'PicFromDisk.png', 'Picture from disk')
```

Ссылка на пример: [sendPictureByUpload.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/sendPictureByUpload.php)

### Создание группы и отправка сообщения в эту группу

```
$chatIds = [
	'11001234567@c.us'
];
$resultCreate = $greenApi->groups->createGroup('GroupName', $chatIds );

if ($resultCreate->code == 200)
	$resultSend = $greenApi->sending->sendMessage($resultCreate->data->chatId, 
	    'Message text');
```

ВАЖНО: Если попытаться создать группу с несуществующим номером WhatsApp 
может заблокировать номер отправителя. Номер в примере не существует.

Ссылка на пример: [createGroupAndSendMessage.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/createGroupAndSendMessage.php)

### Получение входящих сообщений через HTTP API

Общая концепция получения данных в Green API описана [здесь](https://green-api.com/docs/api/receiving/)
Для старта получения сообщений через HTTP API требуется выполнить метод библиотеки:

```
greenAPI.webhooks.startReceivingNotifications(onEvent)
```

onEvent - ваш метод, который должен содержать параметры:
Параметр |  Описание
----- | -----
typeWebhook | тип полученного сообщения (строка)
body | тело сообщения (json)

Типы и форматы тел сообщений [здесь](https://green-api.com/docs/api/receiving/notifications-format/)

Этот метод будет вызываться при получении входящего сообщения. Далее обрабатываете сообщения согласно бизнес-логике вашей системы.

## Список примеров

| Описание                                             | Модуль                                                                                                                                   |
|------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------|
| Пример отправки текста                               | [sendTextMessage.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/sendTextMessage.php)                     |
| Пример отправки картинки по URL                      | [sendPictureByLink.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/sendPictureByLink.php)                 |
| Пример отправки картинки загрузкой с диска           | [sendPictureByUpload.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/sendPictureByUpload.php)             |
| Пример создание группы и отправка сообщения в группу | [createGroupAndSendMessage.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/createGroupAndSendMessage.php) |
| Пример получения входящих уведомлений                | [receiveNotification.php](https://github.com/green-api/whatsapp-api-client-php/blob/master/examples/receiveNotification.php)             |

## Полный список методов библиотеки

| Метод API                              | Описание                                                                                                                  | Documentation link                                                                                       |
|----------------------------------------|---------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------|
| `account.getSettings`                  | Метод предназначен для получения текущих настроек аккаунта                                                                | [GetSettings](https://green-api.com/docs/api/account/GetSettings/)                                       |
| `account.setSettings`                  | Метод предназначен для установки настроек аккаунта                                                                        | [SetSettings](https://green-api.com/docs/api/account/SetSettings/)                                       |
| `account.getStateInstance`             | Метод предназначен для получения состояния аккаунта                                                                       | [GetStateInstance](https://green-api.com/docs/api/account/GetStateInstance/)                             |
| `account.getStatusInstance`            | Метод предназначен для получения состояния сокета соединения инстанса аккаунта с WhatsApp                                 | [GetStatusInstance](https://green-api.com/docs/api/account/GetStatusInstance/)                           |
| `account.reboot`                       | Метод предназначен для перезапуска аккаунта                                                                               | [Reboot](https://green-api.com/docs/api/account/Reboot/)                                                 |
| `account.logout`                       | Метод предназначен для разлогинивания аккаунта                                                                            | [Logout](https://green-api.com/docs/api/account/Logout/)                                                 |
| `account.qr`                           | Метод предназначен для получения QR-кода                                                                                  | [QR](https://green-api.com/docs/api/account/QR/)                                                         |
| `account.setProfilePicture`            | Метод предназначен для установки аватара аккаунта                                                                         | [SetProfilePicture](https://green-api.com/docs/api/account/SetProfilePicture/)                           |
| `account.getAuthorizationCode`         | Метод предназначен для авторизации инстанса по номеру телефона                                                            | [GetAuthorizationCode](https://green-api.com/docs/api/account/GetAuthorizationCode/)                     |
| `groups.createGroup`                   | Метод предназначен для создания группового чата                                                                           | [CreateGroup](https://green-api.com/docs/api/groups/CreateGroup/)                                        |
| `groups.updateGroupName`               | Метод изменяет наименование группового чата                                                                               | [UpdateGroupName](https://green-api.com/docs/api/groups/UpdateGroupName/)                                |
| `groups.getGroupData`                  | Метод получает данные группового чата                                                                                     | [GetGroupData](https://green-api.com/docs/api/groups/GetGroupData/)                                      |
| `groups.addGroupParticipant`           | Метод добавляет участника в групповой чат                                                                                 | [AddGroupParticipant](https://green-api.com/docs/api/groups/AddGroupParticipant/)                        |
| `groups.removeGroupParticipant`        | Метод удаляет участника из группового чата                                                                                | [RemoveGroupParticipant](https://green-api.com/docs/api/groups/RemoveGroupParticipant/)                  |
| `groups.setGroupAdmin`                 | Метод назначает участника группового чата администратором                                                                 | [SetGroupAdmin](https://green-api.com/docs/api/groups/SetGroupAdmin/)                                    |
| `groups.removeAdmin`                   | Метод лишает участника прав администрирования группового чата                                                             | [RemoveAdmin](https://green-api.com/docs/api/groups/RemoveAdmin/)                                        |
| `groups.setGroupPicture`               | Метод устанавливает аватар группы                                                                                         | [SetGroupPicture](https://green-api.com/docs/api/groups/SetGroupPicture/)                                |
| `groups.leaveGroup`                    | Метод производит выход пользователя текущего аккаунта из группового чата                                                  | [LeaveGroup](https://green-api.com/docs/api/groups/LeaveGroup/)                                          |
| `journals.getChatHistory`              | Метод возвращает историю сообщений чата                                                                                   | [GetChatHistory](https://green-api.com/docs/api/journals/GetChatHistory/)                                |
| `journals.lastIncomingMessages`        | Метод возвращает крайние входящие сообщения аккаунта                                                                      | [LastIncomingMessages](https://green-api.com/docs/api/journals/LastIncomingMessages/)                    |
| `journals.lastOutgoingMessages`        | Метод возвращает крайние отправленные сообщения аккаунта                                                                  | [LastOutgoingMessages](https://green-api.com/docs/api/journals/LastOutgoingMessages/)                    |
| `queues.showMessagesQueue`             | Метод предназначен для получения списка сообщений, находящихся в очереди на отправку                                      | [ShowMessagesQueue](https://green-api.com/docs/api/queues/ShowMessagesQueue/)                            |
| `queues.clearMessagesQueue`            | Метод предназначен для очистки очереди сообщений на отправку                                                              | [ClearMessagesQueue](https://green-api.com/docs/api/queues/ClearMessagesQueue/)                          |
| `marking.readChat`                     | Метод предназначен для отметки сообщений в чате прочитанными                                                              | [ReadChat](https://green-api.com/docs/api/marks/ReadChat/)                                               |
| `receiving.receiveNotification`        | Метод предназначен для получения одного входящего уведомления из очереди уведомлений                                      | [ReceiveNotification](https://green-api.com/docs/api/receiving/technology-http-api/ReceiveNotification/) |
| `receiving.deleteNotification`         | Метод предназначен для удаления входящего уведомления из очереди уведомлений                                              | [DeleteNotification](https://green-api.com/docs/api/receiving/technology-http-api/DeleteNotification/)   |
| `receiving.downloadFile`               | Метод предназначен для скачивания принятых и отправленных файлов                                                          | [DownloadFile](https://green-api.com/docs/api/receiving/files/DownloadFile/)                             |
| `sending.sendMessage`                  | Метод предназначен для отправки текстового сообщения в личный или групповой чат                                           | [SendMessage](https://green-api.com/docs/api/sending/SendMessage/)                                       |
| `sending.sendButtons`                  | Метод предназначен для отправки сообщения с кнопками в личный или групповой чат                                           | [SendButtons](https://green-api.com/docs/api/sending/SendButtons/)                                       |
| `sending.sendTemplateButtons`          | Метод предназначен для отправки сообщения с интерактивными кнопками из перечня шаблонов в личный или групповой чат        | [SendTemplateButtons](https://green-api.com/docs/api/sending/SendTemplateButtons/)                       |
| `sending.sendListMessage`              | Метод предназначен для отправки сообщения с кнопкой выбора из списка значений в личный или групповой чат                  | [SendListMessage](https://green-api.com/docs/api/sending/SendListMessage/)                               |
| `sending.sendFileByUpload`             | Метод предназначен для отправки файла, загружаемого через форму (form-data)                                               | [SendFileByUpload](https://green-api.com/docs/api/sending/SendFileByUpload/)                             |
| `sending.sendFileByUrl`                | Метод предназначен для отправки файла, загружаемого по ссылке                                                             | [SendFileByUrl](https://green-api.com/docs/api/sending/SendFileByUrl/)                                   |
| `sending.sendLocation`                 | Метод предназначен для отправки сообщения геолокации                                                                      | [SendLocation](https://green-api.com/docs/api/sending/SendLocation/)                                     |
| `sending.sendContact`                  | Метод предназначен для отправки сообщения с контактом                                                                     | [SendContact](https://green-api.com/docs/api/sending/SendContact/)                                       |
| `sending.sendLink`                     | Метод предназначен для отправки сообщения со ссылкой, по которой будут добавлены превью изображения, заголовок и описание | [SendLink](https://green-api.com/docs/api/sending/SendLink/)                                             |
| `sending.forwardMessages`              | Метод предназначен для пересылки сообщений в личный или групповой чат                                                     | [ForwardMessages](https://green-api.com/docs/api/sending/ForwardMessages/)                               |
| `serviceMethods.checkWhatsapp`         | Метод проверяет наличие аккаунта WhatsApp на номере телефона                                                              | [CheckWhatsapp](https://green-api.com/docs/api/service/CheckWhatsapp/)                                   |
| `serviceMethods.getAvatar`             | Метод возвращает аватар корреспондента или группового чата                                                                | [GetAvatar](https://green-api.com/docs/api/service/GetAvatar/)                                           |
| `serviceMethods.getContacts`           | Метод предназначен для получения списка контактов текущего аккаунта                                                       | [GetContacts](https://green-api.com/docs/api/service/GetContacts/)                                       |
| `serviceMethods.getContactInfo`        | Метод предназначен для получения информации о контакте                                                                    | [GetContactInfo](https://green-api.com/docs/api/service/GetContactInfo/)                                 |
| `serviceMethods.editMessage`           | Метод редактирует сообщение в чате                                                                                        | [EditMessage](https://green-api.com/docs/api/service/editMessage/)                                       |
| `serviceMethods.deleteMessage`         | Метод удаляет сообщение из чата                                                                                           | [DeleteMessage](https://green-api.com/docs/api/service/deleteMessage/)                                   |
| `serviceMethods.archiveChat`           | Метод архивирует чат                                                                                                      | [ArchiveChat](https://green-api.com/docs/api/service/archiveChat/)                                       |
| `serviceMethods.unarchiveChat`         | Метод разархивирует чат                                                                                                   | [UnarchiveChat](https://green-api.com/docs/api/service/unarchiveChat/)                                   |
| `serviceMethods.setDisappearingChat`   | Метод предназначен для изменения настроек исчезающих сообщений в чатах                                                    | [SetDisappearingChat](https://green-api.com/docs/api/service/SetDisappearingChat/)                       |
| `webhooks.startReceivingNotifications` | Метод предназначен для старта получения новых уведомлений                                                                 |                                                                                                          |
| `webhooks.stopReceivingNotifications`  | Метод предназначен для остановки получения новых уведомлений                                                              |                                                                                                          |
| `statuses.sendTextStatus`              | Метод предназначен для отправки текстового статуса                                                                        | [SendTextStatus](https://green-api.com/docs/api/statuses/SendTextStatus/)                                |
| `statuses.sendVoiceStatus`             | Метод предназначен для отправки голосового статуса                                                                        | [SendVoiceStatus](https://green-api.com/docs/api/statuses/SendVoiceStatus/)                              |
| `statuses.sendMediaStatus`             | Метод предназначен для отправки медиа-файлов                                                                              | [SendMediaStatus](https://green-api.com/docs/api/statuses/SendMediaStatus/)                              |
| `statuses.getIncomingStatuses`         | Метод возвращает крайние входящие статусы аккаунта                                                                        | [GetIncomingStatuses](https://green-api.com/docs/api/statuses/GetIncomingStatuses/)                      |
| `statuses.getOutgoingStatuses`         | Метод возвращает крайние отправленные статусы аккаунта                                                                    | [GetOutgoingStatuses](https://green-api.com/docs/api/statuses/GetOutgoingStatuses/)                      |
| `statuses.getStatusStatistic`          | Метод возвращает массив получателей со статусами, отмеченных как отправлено/доставлено/прочитано, для данного статуса     | [GetStatusStatistic](https://green-api.com/docs/api/statuses/GetStatusStatistic/)                        |
| `partner.getInstances`                 | Метод предназначен для получения инстансов аккаунта                                                                       | [GetInstances](https://green-api.com/en/docs/partners/getInstances/)                                     |
| `partner.createInstance`               | Метод предназначен для создания инстанса в аккаунте                                                                       | [CreateInstance](https://green-api.com/en/docs/partners/createInstance/)                                 |
| `partner.deleteInstanceAccount`        | Метод предназначен для удаления инстанса в аккаунте                                                                       | [GetStatusStatistic](https://green-api.com/en/docs/partners/deleteInstanceAccount/)                      |

## Документация по методам сервиса

[https://green-api.com/docs/api/](https://green-api.com/docs/api/)

## Лицензия

Лицензировано на условиях MIT. Смотрите файл [LICENSE](LICENSE)
