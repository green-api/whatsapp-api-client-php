<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Sending {

	private $greenApi;

	/**
	 * @param GreenApiClient $greenApi
	 */
	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

	/**
	 * The method is aimed for sending a button message to a personal or a group chat. The message will be added to
	 * the send queue. Checking whatsapp authorization on the phone (i.e. availability in linked devices) is not
	 * performed. The message will be kept for 24 hours in the queue and will be sent immediately after phone
	 * authorization. The rate at which messages are sent from the queue is managed by Message sending delay parameter.
	 *
	 * @param string $chatId
	 * @param string $message
	 * @param string $footer
	 * @param array $buttons
	 * @param string|null $quotedMessageId
	 * @param bool $archiveChat
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendButtons/
	 */
	public function sendButtons(
		string $chatId, string $message, string $footer, array $buttons,
		string $quotedMessageId = null, bool $archiveChat = false
	): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'message' => $message,
			'footer' => $footer,
			'buttons' => $buttons,
		];

		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}

		if ( $archiveChat ) {
			$requestBody['archiveChat'] = $archiveChat;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/sendButtons/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for sending a contact message. Contact visit card is created and sent to a chat. The message
	 * will be added to the send queue. Linked device not required when sending. Messages will be kept for 24 hours
	 * in the queue until account will be authorized The rate at which messages are sent from the queue is managed
	 * by Message sending delay parameter.
	 *
	 * @param string $chatId
	 * @param array $contact
	 * @param string|null $quotedMessageId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendContact/
	 */
	public function sendContact( string $chatId, array $contact, string $quotedMessageId = null ): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'contact' => $contact
		];

		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendContact/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for sending a file uploaded by form (form-data). The message will be added to the send queue.
	 * The rate at which messages are sent from the queue is managed by Message sending delay parameter. Video, audio
	 * and image files available for viewing and listening to are sent as in native-mode WhatsApp. Documents are sent
	 * in the same way as in native-mode WhatsApp. Outgoing file type and send method is determined by the file extension.
	 * Description is only added to images and video.The maximum size of outgoing files is 37 MB.
	 *
	 * @param string $chatId
	 * @param string $path
	 * @param string|null $fileName
	 * @param string|null $caption
	 * @param string|null $quotedMessageId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendFileByUpload/
	 */
	public function sendFileByUpload(
		string $chatId, string $path, string $fileName = null, string $caption = null, string $quotedMessageId = null
	): stdClass {

		if ( ! $fileName ) {
			$fileName = basename( $path );
		}

		$requestBody = [
			'chatId' => $chatId,
			'fileName' => $fileName,
			'file' => curl_file_create( $path ),
		];
		$requestBody['file']->mime = mime_content_type( $path );

		if ( $caption ) {
			$requestBody['caption'] = $caption;
		}
		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendFileByUpload/{{apiTokenInstance}}', $requestBody, true );
	}


	/**
	 * The method is aimed for sending a file uploaded by Url The message will be added to the send queue. The rate at
	 * which messages are sent from the queue is managed by Message sending delay parameter.Video, audio and image files
	 * available for viewing and listening to are sent as in native-mode WhatsApp. Documents are sent in the same way
	 * as in native-mode WhatsApp. Outgoing file type and send method is determined by the file extension. Description is
	 * only added to images and video.The maximum size of outgoing files is 37 MB.
	 *
	 * @param string $chatId
	 * @param string $urlFile
	 * @param string|null $fileName
	 * @param string|null $caption
	 * @param string|null $quotedMessageId
	 * @param bool $archiveChat
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendFileByUrl/
	 */
	public function sendFileByUrl(
		string $chatId, string $urlFile, string $fileName = null, string $caption = null, string $quotedMessageId = null,
		bool $archiveChat = false
	): stdClass {

		if ( ! $fileName ) {
			$fileName = basename( $urlFile );
		}

		$requestBody = [
			'chatId' => $chatId,
			'urlFile' => $urlFile,
			'fileName' => $fileName,
		];

		if ( $caption ) {
			$requestBody['caption'] = $caption;
		}

		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}

		if ( $archiveChat ) {
			$requestBody['archiveChat'] = $archiveChat;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendFileByUrl/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for sending a message with a link, by which an image preview, title and description will be
	 * added. Linked device not required when sending. Messages will be kept for 24 hours in the queue until account will
	 * be authorized Image, title and description are obtained from Open Graph page template being linked to. The message
	 * will be added to the send queue. The rate at which messages are sent from the queue is managed by Messages sending
	 * delay parameter.
	 *
	 * @param string $chatId
	 * @param string $urlLink
	 * @param string|null $quotedMessageId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendLink/
	 */
	public function sendLink( string $chatId, string $urlLink, string $quotedMessageId = null ): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'urlLink' => $urlLink
		];

		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendLink/{{apiTokenInstance}}', $requestBody );
	}


	/**
	 * The method is aimed for sending a message with a select button from a list of values to a personal or a group chat.
	 * The message will be added to the send queue. Checking whatsapp authorization on the phone (i.e. availability in
	 * linked devices) is not performed. The message will be kept for 24 hours in the queue and will be sent immediately
	 * after phone authorization. The rate at which messages are sent from the queue is managed by Message sending delay
	 * parameter.
	 *
	 * @param string $chatId
	 * @param string $message
	 * @param array $sections
	 * @param string|null $title
	 * @param string|null $footer
	 * @param string|null $buttonText
	 * @param string|null $quotedMessageId
	 * @param bool $archiveChat
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendListMessage/
	 */
	public function sendListMessage(
		string $chatId, string $message, array $sections, string $title = null, string $footer = null,
		string $buttonText = null, string $quotedMessageId = null, bool $archiveChat = false
	): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'message' => $message,
			'sections' => $sections,
		];

		if ( $title ) {
			$requestBody['title'] = $title;
		}
		if ( $footer ) {
			$requestBody['footer'] = $footer;
		}
		if ( $buttonText ) {
			$requestBody['buttonText'] = $buttonText;
		}
		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}
		if ( $archiveChat ) {
			$requestBody['archiveChat'] = $archiveChat;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendListMessage/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for sending location message. The message will be added to the send queue. Linked device not
	 * required when sending. Messages will be kept for 24 hours in the queue until account will be authorized.
	 * The rate at which messages are sent from the queue is managed by Message sending delay parameter.
	 *
	 * @param string $chatId
	 * @param float $latitude
	 * @param float $longitude
	 * @param string|null $nameLocation
	 * @param string|null $address
	 * @param string|null $quotedMessageId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendLocation/
	 */
	public function sendLocation(
		string $chatId, float $latitude, float $longitude, string $nameLocation = null, string $address = null,
		string $quotedMessageId = null
	): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'latitude' => $latitude,
			'longitude' => $longitude,
		];

		if ( $nameLocation ) {
			$requestBody['nameLocation'] = $nameLocation;
		}
		if ( $address ) {
			$requestBody['address'] = $address;
		}
		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendLocation/{{apiTokenInstance}}', $requestBody );
	}


	/**
	 * The method is aimed for sending a text message to a personal or a group chat. The message will be added to
	 * the send queue. Linked device not required when sending. Messages will be kept for 24 hours in the queue until
	 * account will be authorized The rate at which messages are sent from the queue is managed by Message sending
	 * delay parameter.
	 *
	 * @param string $chatId
	 * @param string $message
	 * @param string|null $quotedMessageId
	 * @param bool $archiveChat
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function sendMessage(
		string $chatId, string $message, string $quotedMessageId = null, bool $archiveChat = false
	): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'message' => $message,
		];

		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}

		if ( $archiveChat ) {
			$requestBody['archiveChat'] = $archiveChat;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendMessage/{{apiTokenInstance}}', $requestBody );
	}


	/**
	 * The method is aimed for sending a message with template list interactive buttons to a personal or a group chat.
	 * The message will be added to the send queue. Checking whatsapp authorization on the phone (i.e. availability in
	 * linked devices) is not performed. The message will be kept for 24 hours in the queue and will be sent immediately
	 * after phone authorization. The rate at which messages are sent from the queue is managed by Message sending delay
	 * parameter.
	 *
	 * @param string $chatId
	 * @param string $message
	 * @param array $templateButtons
	 * @param string|null $footer
	 * @param string|null $quotedMessageId
	 * @param bool $archiveChat
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendTemplateButtons/
	 */
	public function sendTemplateButtons(
		string $chatId, string $message, array $templateButtons, string $footer = null,
		string $quotedMessageId = null, bool $archiveChat = false
	): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'message' => $message,
			'templateButtons' => $templateButtons,
		];

		if ( $footer ) {
			$requestBody['footer'] = $footer;
		}
		if ( $quotedMessageId ) {
			$requestBody['quotedMessageId'] = $quotedMessageId;
		}
		if ( $archiveChat ) {
			$requestBody['archiveChat'] = $archiveChat;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SendTemplateButtons/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is intended for forwarding messages to a personal or group chat. The forwarded messages will be added
	 * to the send queue. Checking whatsapp authorization on the phone (i.e. availability in linked devices) is not performed.
	 * The message will be kept for 24 hours in the queue and will be sent immediately after phone authorization.
	 * The rate at which messages are sent from the queue is managed by Message sending delay parameter.
	 *
	 * @param string $chatId
	 * @param string $chatIdFrom
	 * @param array $messages
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/ForwardMessages/
	 */
	public function forwardMessages( string $chatId, string $chatIdFrom, array $messages ): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'chatIdFrom' => $chatIdFrom,
			'messages' => $messages
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/ForwardMessages/{{apiTokenInstance}}', $requestBody );
	}
}
