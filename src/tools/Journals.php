<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Journals
{
    /**
     * @param GreenApiClient $greenApi
     */
    private $greenApi;

    public function __construct(GreenApiClient $greenApi)
    {
        $this->greenApi = $greenApi;
    }

    /**
     * The method returns the chat message history.
     *
     * @param string $chatId
     * @param int|null $count
     *
     * @return stdClass
     *
     * @link https://green-api.com/en/docs/api/journals/GetChatHistory/
     */
    public function getChatHistory(string $chatId, int $count = null): stdClass
    {
        $requestBody = [
            'chatId' => $chatId,
        ];

        if (!is_null($count)) {
            $requestBody['count'] = $count;
        }

        return $this->greenApi->request(
            'POST', '{{host}}/waInstance{{idInstance}}/GetChatHistory/{{apiTokenInstance}}', $requestBody
        );
    }

    /**
     * The method returns the chat message.
     *
     * @param string $chatId
     * @param string $idMessage
     *
     * @return stdClass
     *
     * @link https://green-api.com/en/docs/api/journals/GetMessage/
     */
    public function getMessage(string $chatId, string $idMessage): stdClass
    {
        $requestBody = [
            'chatId' => $chatId,
            'idMessage' => $idMessage,
        ];

        return $this->greenApi->request(
            'POST', '{{host}}/waInstance{{idInstance}}/getMessage/{{apiTokenInstance}}', $requestBody
        );
    }

    /**
     * The method returns the chat message history.
     *
     * @param int|null $minutes
     *
     * @return stdClass
     *
     * @link https://green-api.com/en/docs/api/journals/LastIncomingMessages/
     */
    public function lastIncomingMessages(int $minutes = null): stdClass
    {
        $requestBody = null;

        if (!is_null($minutes)) {
            $requestBody['minutes'] = $minutes;
        }

        return $this->greenApi->request(
            'GET', '{{host}}/waInstance{{idInstance}}/LastIncomingMessages/{{apiTokenInstance}}', $requestBody
        );
    }

    /**
     * The method returns the last outgoing messages of the account.
     * Outgoing messages are stored on the server for 24 hours.
     *
     * @param int|null $minutes
     *
     * @return stdClass
     *
     * @link https://green-api.com/en/docs/api/journals/LastOutgoingMessages/
     */
    public function lastOutgoingMessages(int $minutes = null): stdClass
    {
        $requestBody = null;

        if (!is_null($minutes)) {
            $requestBody['minutes'] = $minutes;
        }

        return $this->greenApi->request(
            'GET', '{{host}}/waInstance{{idInstance}}/LastOutgoingMessages/{{apiTokenInstance}}', $requestBody
        );
    }
}
