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
	 * The method is aimed for sending a text status. 
	 *
	 * @param string $message
     * @param string $backgroundColor
     * @param string $font
     * @param array $participants
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/statuses/SendTextStatus/
	 */
	public function sendTextStatus( string $message, string $backgroundColor, string $font, array $participants ): stdClass {

		$requestBody = [
			'message' => $message,
            'backgroundColor' => $backgroundColor,
            'font' => $font,
            'participants' => $participants,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/sendTextStatus/{{apiTokenInstance}}', $requestBody );
	}

    /**
	 * The method is aimed for sending a voice status.
	 *
	 * @param string $urlFile
     * @param string $fileName
     * @param string $backgroundColor
     * @param array $participants
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/statuses/SendVoiceStatus/
	 */
	public function sendVoiceStatus( string $urlFile, string $fileName, string $backgroundColor, array $participants ): stdClass {

		$requestBody = [
			'urlFile' => $urlFile,
            'fileName' => $fileName,
            'backgroundColor' => $backgroundColor,
            'participants' => $participants,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/sendVoiceStatus/{{apiTokenInstance}}', $requestBody );
	}

    /**
	 * The method is aimed for sending a pictures or video status. 
	 *
	 * @param string $urlFile
     * @param string $fileName
     * @param string $caption
     * @param array $participants
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/statuses/SendMediaStatus/
	 */
	public function sendMediaStatus( string $urlFile, string $fileName, string $caption, array $participants ): stdClass {

		$requestBody = [
			'urlFile' => $urlFile,
            'fileName' => $fileName,
            'caption' => $caption,
            'participants' => $participants,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/sendMediaStatus/{{apiTokenInstance}}', $requestBody );
	}

    /**
	 * The method returns the incoming status messages of the instance. In the default mode the incoming status messages for 24 hours are returned.
	 *
	 * @param int|null $minutes
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/statuses/GetIncomingStatuses/
	 */
	public function getIncomingStatuses( int $minutes = null ): stdClass {

		$requestBody = null;

        if ($minutes) {
            $requestBody['minutes'] = $minutes;
        }

        return $this->greenApi->request(
            'GET', '{{host}}/waInstance{{idInstance}}/getIncomingStatuses/{{apiTokenInstance}}', $requestBody
        );
	}

    /**
	 * The method returns the incoming status messages of the instance. In the default mode the incoming status messages for 24 hours are returned.
	 *
	 * @param int|null $minutes
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/statuses/GetIncomingStatuses/
	 */
	public function getIncomingStatuses( int $minutes = null ): stdClass {

		$requestBody = null;

        if ($minutes) {
            $requestBody['minutes'] = $minutes;
        }

        return $this->greenApi->request(
            'GET', '{{host}}/waInstance{{idInstance}}/getIncomingStatuses/{{apiTokenInstance}}', $requestBody
        );
	}

    /**
	 * The method returns the outgoing statuses of the account. In the default mode the outgoing status messages for 24 hours are returned.
	 *
	 * @param int|null $minutes
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/statuses/GetOutgoingStatuses/
	 */
	public function getOutgoingStatuses( int $minutes = null ): stdClass {

		$requestBody = null;

        if ($minutes) {
            $requestBody['minutes'] = $minutes;
        }

        return $this->greenApi->request(
            'GET', '{{host}}/waInstance{{idInstance}}/getOutgoingStatuses/{{apiTokenInstance}}', $requestBody
        );
	}

    /**
	 * The method returns the outgoing statuses of the account. In the default mode the outgoing status messages for 24 hours are returned.
	 *
	 * @param string $idMessage
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/statuses/GetStatusStatistic/
	 */
	public function getStatusStatistic( string $idMessage ): stdClass {

		$requestBody = null;

        return $this->greenApi->request(
            'GET', 
            '{{host}}/waInstance{{idInstance}}/getStatusStatistic/{{apiTokenInstance}}', 
            $requestBody,
            ['idMessage' => $idMessage]
        );
	}
}