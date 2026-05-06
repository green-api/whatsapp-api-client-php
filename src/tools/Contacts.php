<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Contacts
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
     * The method adds a contact to the user's contact list.
     *
     * @param string $chatId
     * @param string $firstName
     * @param string|null $lastName
     * @param bool $saveInAddressbook
     *
     * @return stdClass
     *
     * @link https://green-api.com/en/docs/api/contacts/AddContact/
     */
    public function addContact(string $chatId, string $firstName, string $lastName = null, bool $saveInAddressbook = true): stdClass
    {
        $requestBody = [
            'chatId' => $chatId,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'saveInAddressbook' => $saveInAddressbook,
        ];

        return $this->greenApi->request(
            'POST', '{{host}}/waInstance{{idInstance}}/addContact/{{apiTokenInstance}}', $requestBody
        );
    }

    /**
     * The method edits the contact in the user's contact list.
     *
     * @param string $chatId
     * @param string $firstName
     * @param string|null $lastName
     * @param bool $saveInAddressbook
     *
     * @return stdClass
     *
     * @link https://green-api.com/en/docs/api/contacts/EditContact/
     */
    public function editContact(string $chatId, string $firstName, string $lastName = null, bool $saveInAddressbook = true): stdClass
    {
        $requestBody = [
            'chatId' => $chatId,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'saveInAddressbook' => $saveInAddressbook,
        ];

        return $this->greenApi->request(
            'POST', '{{host}}/waInstance{{idInstance}}/editContact/{{apiTokenInstance}}', $requestBody
        );
    }

    /**
     * The method deletes the contact from the user's contact list.
     *
     * @param string $chatId
     *
     * @return stdClass
     *
     * @link https://green-api.com/en/docs/api/contacts/DeleteContact/
     */
    public function deleteContact(string $chatId): stdClass
    {
        $requestBody = [
            'chatId' => $chatId,
        ];

        return $this->greenApi->request(
            'POST', '{{host}}/waInstance{{idInstance}}/deleteContact/{{apiTokenInstance}}', $requestBody
        );
    }
}