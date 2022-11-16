<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Queues {

	/**
	 * @param GreenApiClient $greenApi
	 */
	private $greenApi;

	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

	/**
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/queues/ClearMessagesQueue/
	 */
	public function clearMessagesQueue(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/ClearMessagesQueue/{{apiTokenInstance}}' );
	}

	/**
	 * The method is aimed for getting a list of messages in the queue to be sent. Messages sending rate is managed by
	 * Messages sending delay parameter.'
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/queues/ShowMessagesQueue/
	 */
	public function showMessagesQueue(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/ShowMessagesQueue/{{apiTokenInstance}}' );
	}
}
