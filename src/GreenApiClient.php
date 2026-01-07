<?php

/*
 * Green-api.com REST API Client
 *
 * Documentation
 * https://green-api.com/en/docs/api/
 */

namespace GreenApi\RestApi;

use GreenApi\RestApi\tools\Account;
use GreenApi\RestApi\tools\Groups;
use GreenApi\RestApi\tools\Journals;
use GreenApi\RestApi\tools\Marking;
use GreenApi\RestApi\tools\Queues;
use GreenApi\RestApi\tools\Receiving;
use GreenApi\RestApi\tools\Sending;
use GreenApi\RestApi\tools\ServiceMethods;
use GreenApi\RestApi\tools\Webhooks;
use GreenApi\RestApi\tools\Statuses;
use GreenApi\RestApi\tools\Partner;
use stdClass;

class GreenApiClient {
	private $host;
    private $media;
	private $idInstance = null;
	private $apiTokenInstance = null;
	private $partnerToken = null;

	/**
	 * @var Account|null
     */
	public $account;
	/**
	 * @var Sending|null
     */
	public $sending;
	/**
	 * @var Groups|null
     */
	public $groups;
	/**
	 * @var Journals|null
     */
	public $journals;
	/**
	 * @var Marking|null
     */
	public $marking;
	/**
	 * @var Queues|null
     */
	public $queues;
	/**
	 * @var Receiving|null
     */
	public $receiving;
	/**
	 * @var ServiceMethods|null
     */
	public $serviceMethods;
	/**
	 * @var Webhooks|null
     */
	public $webhooks;
	/**
	 * @var Statuses|null
     */
	public $statuses;
    /**
     * @var Partner|null 
     */
	public $partner = null;

	public function __construct( $idInstance = null, $apiTokenInstance = null, $partnerToken = null, $host = "https://api.green-api.com", $media = "https://media.green-api.com" ) {

		$this->idInstance = $idInstance;
		$this->apiTokenInstance = $apiTokenInstance;
		$this->host = $host;
        $this->media = $media;
        $this->partnerToken = $partnerToken;
        
        if ($this->idInstance && $this->apiTokenInstance) {
            $this->account = new Account( $this );
            $this->groups = new Groups( $this );
            $this->journals = new Journals( $this );
            $this->marking = new Marking( $this );
            $this->queues = new Queues( $this );
            $this->receiving = new Receiving( $this );
            $this->sending = new Sending( $this );
            $this->serviceMethods = new ServiceMethods( $this );
            $this->webhooks = new Webhooks( $this );
            $this->statuses = new Statuses( $this );
        }

        if ($this->partnerToken) {
            $this->partner = new Partner( $this );
        }
	}

	/**
	 * @param string $method
	 * @param string $url
	 * @param array|null $payload
	 * @param bool $is_files
	 * @param string|null $mime_type
	 * @param string|null $path
	 *
	 * @return stdClass
	 */
	public function request( string $method, string $url, array $payload = null, bool $is_files = false,
		string $mime_type = null, string $path = null
	): stdClass {
		$url = str_replace( '{{host}}', $this->host, $url );
        $url = str_replace( '{{media}}', $this->media, $url );
		$url = str_replace( '{{idInstance}}', $this->idInstance, $url );
		$url = str_replace( '{{apiTokenInstance}}', $this->apiTokenInstance, $url );
        
        if ($this->partner) {
            $url = str_replace( '{{partnerToken}}', $this->partnerToken, $url );    
        }

		$method = strtoupper( $method );
		$curl = curl_init();

		$payloadData = null;
		$headers = null;

		if ( $payload ) {
			if ( ! $is_files ) {
				$headers = array( 'Content-Type: application/json; charset=utf-8' );
				curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
				$payloadData = json_encode( $payload, JSON_UNESCAPED_UNICODE );
			} else {
				if ( $mime_type ) {
					$headers = array( 'Content-Type: ' . $mime_type );
					curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
				}
				$payloadData = $payload;
			}
		}

		switch ( $method ) {
			case 'POST':
				if ( ! $headers && ! $is_files ) {
					curl_setopt( $curl, CURLOPT_POST, count( $payload ) );
				} elseif ( $is_files ) {
					curl_setopt( $curl, CURLOPT_POST, count( $payloadData ) );
				}
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $payloadData );
				break;
			case 'PUT':
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $payloadData );
				break;
			case 'DELETE':
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $payloadData );
				break;
			case 'POST_BINARY':
				$mime_type = mime_content_type( $path );
				$headers = array( 
					'Content-Type: ' . $mime_type ,
					'GA-Filename:  ' . basename($path)
			);
				$filesize = filesize($path);
				$stream = fopen($path, 'r');
				curl_setopt( $curl, CURLOPT_PUT, true );
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
				curl_setopt( $curl, CURLOPT_INFILE, $stream );
				curl_setopt( $curl, CURLOPT_INFILESIZE, $filesize );
				curl_setopt( $curl, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
				curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
				break;
			default:
				if ( ! empty( $payload ) ) {
					$url .= '?' . http_build_query( $payload );
				}
		}

		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 30 );

		$response = curl_exec( $curl );
		$header_size = curl_getinfo( $curl, CURLINFO_HEADER_SIZE );
		$headerCode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		$responseBody = substr( $response, $header_size );
		$curlErrors = curl_error( $curl );

		curl_close( $curl );

		if ( $responseBody === 'null' ) {
			$responseBodyJson = null;
		} else {
			$responseBodyJson = json_decode( $responseBody );
		}

		$result = new stdClass();

		if ( !empty( $curlErrors ) ) {
			$result->code = 0;
			$result->error = $curlErrors;
			return $result;
		}

		$result->code = $headerCode;

		if ( json_last_error() === JSON_ERROR_NONE ) {
			if ( $headerCode === 200 ) {
				$result->data = $responseBodyJson;
				$result->error = null;
			} else {
				$result->data = null;
				$result->error = $responseBodyJson;
			}
		} else {
			$result->data = null;
			$result->error = $responseBody;
		}

		return $result;
	}
}
