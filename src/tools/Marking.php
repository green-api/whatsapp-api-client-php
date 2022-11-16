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
	 * The method returns the chat message history.
	 *
	 * @param string $chatId
	 * @param string $idMessage
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/marks/ReadChat/
	 */
	public function readChat( string $chatId, string $idMessage ): stdClass {

		$requestBody = [
			'chatId' => $chatId,
			'idMessage' => $idMessage,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/ReadChat/{{apiTokenInstance}}', $requestBody );
	}
}
