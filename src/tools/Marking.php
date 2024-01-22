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
	 * @param string|null $idMessage
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/marks/ReadChat/
	 */
	public function readChat( string $chatId, string $idMessage = null ): stdClass {

		$requestBody = [
			'chatId' => $chatId
		];

		if (!is_null($idMessage)) {
			$requestBody['idMessage'] = $idMessage;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/ReadChat/{{apiTokenInstance}}', $requestBody );
	}
}
