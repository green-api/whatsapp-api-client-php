<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Receiving {

	/**
	 * @param GreenApiClient $greenApi
	 */
	private $greenApi;

	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

	/**
	 * The method is aimed for downloading incoming and outgoing files. Links to incoming files are transmitted
	 * in Incoming messages, and you can also get them using LastIncomingMessages method. You can get links to outgoing
	 * files using LastOutgoingMessages method.Files storage period and, accordingly, the capability to download
	 * them is limited to 24 hours.
	 *
	 * @param string $idMessage
	 *
	 * @return stdClass
	 */
	public function downloadFile( string $idMessage ): stdClass {

		$requestBody = [
			'idMessage' => $idMessage,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/DownloadFile/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for receiving one incoming notification from the notifications queue. ReceiveNotification
	 * method waits for a notification receipt for 20 sec. The method call ends with an empty response if a timeout is
	 * reached. If a notification comes to the queue within 20 seconds, the method call is completed, and the method
	 * returns the received notification.After receiving and processing an incoming notification, you need to delete the
	 * notification from the queue. This requires you to run DeleteNotification method. After calling DeleteNotification
	 * method, the notification will be considered received and processed and will be permanently deleted from the queue.
	 * Therefore, the next call of ReceiveNotification method will return the next notification from the queue in the
	 * order in which notifications come to the queue.Incoming notifications are stored in the queue for 24 hours.
	 * Notifications are sent from the queue in FIFO order
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/receiving/technology-http-api/ReceiveNotification/
	 */
	public function receiveNotification(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/ReceiveNotification/{{apiTokenInstance}}' );
	}

	/**
	 * The method is aimed for deleting an incoming notification from the notification queue. To specify what notification
	 * to delete, use receiptId parameter. After receiving and processing an incoming notification, you need to delete
	 * the notification from the queue. This requires you to run this method. After calling the method, the notification
	 * will be considered received and processed and will be permanently deleted from the queue. Therefore, the next call
	 * of ReceiveNotification method will return the next notification from the queue in the order in which notifications
	 * come to the queue.Incoming notifications are stored in the queue for 24 hours.Notifications are sent from the
	 * queue in FIFO order
	 *
	 * @param string $receiptId
	 *
	 * @return stdClass
	 */
	public function deleteNotification( string $receiptId ): stdClass {

		return $this->greenApi->request( 'DELETE',
			'{{host}}/waInstance{{idInstance}}/DeleteNotification/{{apiTokenInstance}}/' . $receiptId );
	}
}
