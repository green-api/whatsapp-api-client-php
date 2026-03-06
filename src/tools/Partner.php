<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Partner {

    /**
     * @param GreenApiClient $greenApi
     */
    private $greenApi;

    /**
     * @param GreenApiClient $greenApi
     */
    public function __construct(GreenApiClient $greenApi) {
        $this->greenApi = $greenApi;
    }

    /**
     * The method is used to getting all the account instances created by the partner.
     *
     * @return stdClass
     * @link https://green-api.com/en/docs/partners/getInstances/
     */
    public function getInstances(): stdClass {
        return $this->greenApi->request('GET',
            '{{host}}/partner/getInstances/{{partnerToken}}');
    }

    /**
     * The method is used to creating a messenger account instance on the partner's part
     *
     * @param $payload
     * @return stdClass
     * @link https://green-api.com/en/docs/partners/createInstance/
     */
    public function createInstance($payload): stdClass {
        $defaultParameters = [
            'name' => null,
            'webhookUrl' => null,
            'webhookUrlToken' => null,
            'delaySendMessagesMilliseconds' => null,
            'markIncomingMessagesReaded' => null,
            'markIncomingMessagesReadedOnReply' => null,
            'outgoingWebhook' => null,
            'outgoingMessageWebhook' => null,
            'outgoingAPIMessageWebhook' => null,
            'stateWebhook' => null,
            'incomingWebhook' => 'yes',
            'deviceWebhook' => null,
            'keepOnlineStatus' => null,
            'pollMessageWebhook' => null,
            'incomingBlockWebhook' => null,
            'incomingCallWebhook' => null,
            'editedMessageWebhook' => null,
            'deletedMessageWebhook' => null,
        ];

        $requestParameters = array_filter(array_merge($defaultParameters, $payload));

        return $this->greenApi->request('POST',
            '{{host}}/partner/createInstance/{{partnerToken}}', $requestParameters);
    }

    /**
     *
     * The method is used to deleting an instance of the partner's account.
     *
     * @param $idInstance
     * @return stdClass
     * @link https://green-api.com/en/docs/partners/deleteInstanceAccount/
     */
    public function deleteInstanceAccount($idInstance): stdClass {
        return $this->greenApi->request('POST',
            '{{host}}/partner/deleteInstanceAccount/{{partnerToken}}', ['idInstance' => $idInstance]);
    }
}
