<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Account {
	private $greenApi;

	/**
	 * @param GreenApiClient $greenApi
	 */
	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

	/**
	 * The method is aimed for getting the current account settings.
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/GetSettings/
	 */
	public function getSettings(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/GetSettings/{{apiTokenInstance}}' );
	}

	/**
	 * The method is aimed for getting the account state.
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/GetStateInstance/
	 */
	public function getStateInstance(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/GetStateInstance/{{apiTokenInstance}}' );
	}

	/**
	 * The method is aimed for getting the status of the account instance socket connection with WhatsApp.
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/GetStatusInstance/
	 */
	public function getStatusInstance(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/GetStatusInstance/{{apiTokenInstance}}' );
	}

	/**
	 * The method is aimed for logging out an account.
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/Logout/
	 */
	public function logout(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/Logout/{{apiTokenInstance}}' );
	}

	/**
	 * The method is aimed for getting QR code. To authorize your account, you have to scan a QR code from
	 * application WhatsApp Business on your phone. You can also get a QR code and authorize your account in your profile.
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/QR/
	 */
	public function qr(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/QR/{{apiTokenInstance}}' );
	}

	/**
	 * The method is aimed for rebooting an account.
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/Reboot/
	 */
	public function reboot(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/Reboot/{{apiTokenInstance}}' );
	}


	/**
	 * The method is aimed for setting an account picture.
	 *
	 * @param string $path
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/SetProfilePicture/
	 */
	public function setProfilePicture( string $path ): stdClass {

		$requestBody = [
			'file' => curl_file_create( $path ),
		];
		$requestBody['file']->mime = 'image/jpeg';

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SetProfilePicture/{{apiTokenInstance}}', $requestBody, true );
	}

	/**
	 * The method is aimed for setting account settings. When this method is requested, the account is rebooted.
	 *
	 * @param array $requestBody
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/SetSettings/
	 */
	public function setSettings( array $requestBody ): stdClass {

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SetSettings/{{apiTokenInstance}}', $requestBody );
	}


	/**
	 * The method is aimed for setting a system proxy. Use the method when you need to reset custom proxy settings to
	 * system ones.
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/account/SetSystemProxy/
	 */
	public function setSystemProxy(): stdClass {

		return $this->greenApi->request( 'GET',
			'{{host}}/waInstance{{idInstance}}/SetSystemProxy/{{apiTokenInstance}}' );
	}
}
