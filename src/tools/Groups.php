<?php

namespace GreenApi\RestApi\tools;

use GreenApi\RestApi\GreenApiClient;
use stdClass;

class Groups {

	/**
	 * @param GreenApiClient $greenApi
	 */
	private $greenApi;

	public function __construct( GreenApiClient $greenApi ) {
		$this->greenApi = $greenApi;
	}

	/**
	 * The method adds a participant to a group chat.
	 *
	 * @param string $groupId
	 * @param string $participantChatId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/AddGroupParticipant/
	 */
	public function addGroupParticipant( string $groupId, string $participantChatId ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
			'participantChatId' => $participantChatId,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/AddGroupParticipant/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method is aimed for creating a group chat.
	 *
	 * @param string $groupName
	 * @param array $chatIds
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/CreateGroup/
	 */
	public function createGroup( string $groupName, array $chatIds ): stdClass {

		$requestBody = [
			'groupName' => $groupName,
			'chatIds' => $chatIds,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/CreateGroup/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method gets group chat data.
	 *
	 * @param string $groupId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/GetGroupData/
	 */
	public function getGroupData( string $groupId ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/GetGroupData/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method makes the current account user leave the group chat.
	 *
	 * @param string $groupId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/LeaveGroup/
	 */
	public function leaveGroup( string $groupId ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/LeaveGroup/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method removes a participant from group chat administration rights.
	 *
	 * @param string $groupId
	 * @param string $participantChatId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/RemoveAdmin/
	 */
	public function removeAdmin( string $groupId, string $participantChatId ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
			'participantChatId' => $participantChatId,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/RemoveAdmin/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method removes a participant from a group chat.
	 *
	 * @param string $groupId
	 * @param string $participantChatId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/RemoveGroupParticipant/
	 */
	public function removeGroupParticipant( string $groupId, string $participantChatId ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
			'participantChatId' => $participantChatId,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/RemoveGroupParticipant/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method sets a group chat participant as an administrator.
	 *
	 * @param string $groupId
	 * @param string $participantChatId
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/SetGroupAdmin/
	 */
	public function setGroupAdmin( string $groupId, string $participantChatId ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
			'participantChatId' => $participantChatId,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SetGroupAdmin/{{apiTokenInstance}}', $requestBody );
	}

	/**
	 * The method sets a group picture.
	 *
	 * @param string $groupId
	 * @param string $path
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/SetGroupPicture/
	 */
	public function setGroupPicture( string $groupId, string $path ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
			'file' => curl_file_create( $path ),
		];
		$requestBody['file']->mime = 'image/jpeg';

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/SetGroupPicture/{{apiTokenInstance}}', $requestBody, true );
	}

	/**
	 * The method changes a group chat name.
	 *
	 * @param string $groupId
	 * @param string $groupName
	 *
	 * @return stdClass
	 * @link https://green-api.com/en/docs/api/groups/UpdateGroupName/
	 */
	public function updateGroupName( string $groupId, string $groupName ): stdClass {

		$requestBody = [
			'groupId' => $groupId,
			'groupName' => $groupName,
		];

		return $this->greenApi->request( 'POST',
			'{{host}}/waInstance{{idInstance}}/UpdateGroupName/{{apiTokenInstance}}', $requestBody );
	}
}
