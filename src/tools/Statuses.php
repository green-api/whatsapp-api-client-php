<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Statuses {

	/**
	 * @param GreenApiClient $greenApi
	 */
	private $greenApi;

	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

    /**
	 * The method is aimed for sending a text status to chats included in participants array. 
	 *
	 * @param string $message
	 * @param array $participants
	 * @param string|null $backgroundColor
	 * @param string|null $font
	 * 
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function sendTextStatus(
		string $message, array $participants, string $backgroundColor = null, string $font = null
	): stdClass {

		$requestBody = [
			'message' => $message,
			'participants' => $participants,
		];

		if ( $backgroundColor ) {
			$requestBody['backgroundColor'] = $backgroundColor;
		}

		if ( $font ) {
			$requestBody['font'] = $font;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/sendTextStatus/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for sending a voice status to chats included in participants array. 
	 *
	 * @param string $urlFile
	 * @param string $fileName
	 * @param array $participants
	 * @param string|null $backgroundColor
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function sendVoiceStatus(
		string $urlFile, string $fileName, array $participants, string $backgroundColor = null
	): stdClass {

		$requestBody = [
			'urlFile' => $urlFile,
			'fileName' => $fileName,
			'participants' => $participants,
		];

		if ( $backgroundColor ) {
			$requestBody['backgroundColor'] = $backgroundColor;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/sendVoiceStatus/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for sending a media (image, video) status to chats included in participants array. 
	 *
	 * @param string $urlFile
	 * @param string $fileName
	 * @param array $participants
	 * @param string|null $caption
	 * 
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function sendMediaStatus(
		string $urlFile, string $fileName, array $participants, string $caption = null
	): stdClass {

		$requestBody = [
			'urlFile' => $urlFile,
			'fileName' => $fileName,
			'participants' => $participants,
		];

		if ( $caption ) {
			$requestBody['caption'] = $caption;
		}

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/sendMediaStatus/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed to return outgoing statuses for last minutes specified, default value of minutes is 1440.
	 *
     * @param int|null $minutes
	 * 
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function getOutgoingStatuses(
		int $minutes = null
	): stdClass {
		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/getOutgoingStatuses/{{apiTokenInstance}}?minutes=' . $minutes);
	}

	/**
	 * The method is aimed to return incmonig statuses for last minutes specified, default value of minutes is 1440.
	 *
     * @param int|null $minutes
	 * 
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function getIncomingStatuses(
		int $minutes = null
	): stdClass {
		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/getIncomingStatuses/{{apiTokenInstance}}?minutes=' . $minutes);
	}

	/**
	 * The method is aimed to return statistics for a status, it will display whether participants have status with idMessage sent, delivered to them or if they already read them.
	 *
     * @param string $idMessage
	 * 
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function getStatusStatistic(
		string $idMessage
	): stdClass {
		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/getStatusStatistic/{{apiTokenInstance}}?idMessage=' . $idMessage);
	}

	/**
	 * The method is aimed to return incmonig statuses for last minutes specified, default value of minutes is 1440.
	 *
     * @param string $idMessage
	 * 
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/sending/SendMessage/
	 */
	public function deleteStatus(
		string $idMessage
	): stdClass {

		$requestBody = [
			'idMessage' => $idMessage,
		];

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/deleteStatus/{{apiTokenInstance}}', $requestBody);
	}
}
