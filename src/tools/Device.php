<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Device {

	/**
	 * @param GreenApiClient $greenApi
	 */
	private $greenApi;

	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

	/**
	 * The method is aimed for getting information about the device (phone) running WhatsApp Business application.
	 *
	 * @return stdClass
	 */
	public function getDeviceInfo(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/GetDeviceInfo/{{apiTokenInstance}}' );
	}
}
