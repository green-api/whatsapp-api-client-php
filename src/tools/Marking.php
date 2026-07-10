<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Marking {

	/**
	 * @param GreenApiClient $greenApi
	 */
	private $greenApi;

	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

	/**
	 * The method is designed to mark chat messages as read. If idMessage is not specified, all unread messages
	 * in the chat will be marked as read.
	 *
	 * @param string $chatId
	 * @param string|null $idMessage
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/marks/ReadChat/
	 */
	public function readChat( string $chatId, ?string $idMessage = null ): stdClass {

		$requestBody = [
			'chatId' => $chatId,
		];

		if ( $idMessage ) {
			$requestBody['idMessage'] = $idMessage;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/ReadChat/{{apiTokenInstance}}', $requestBody );
	}
}
