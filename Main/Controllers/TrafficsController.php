<?php

namespace Main\Controllers;

use Main\Services\SessionService as SessionService;

class TrafficsController extends \Main\Controller
{

    public SessionService $SessionService;

    function __construct() {
		$this->SessionService = new SessionService();
	}

	function saveTraffic() {

		$traffic = $this->getModel("Traffic");
		$traffic->select(" session_id, JSON_EXTRACT(traffic, '$.name') as name ");
		$traffic->column['session_id'] = $this->SessionService->sessionHandler->get("id");
		
		$response = $traffic->getBySessionId();

		if($response) {
			for($i=0; $i<count($response); $i++) {
				$arr[$response[$i]['session_id']][] = $response[$i]['name'];
			}
		}

		if(!isset($arr[ $traffic->column['session_id'] ]) || !in_array($_POST['name'], $arr[ $traffic->column['session_id'] ]) || !$response) {
			
			$traffic->select("");
			$traffic->saveNew(array(
				"traffic" => json_encode([
					"name" => $_POST['name'],
					"url" => $_POST['url']
				]),
				"account_id" => (isset($_POST['account_id']) ? $_POST['account_id'] : 0),
				"session_id" => $this->SessionService->sessionHandler->get("id"),
				"created_at" => DATE_NOW,
				"user_agent" => json_encode($_POST['client_info'])
			));

			$this->SessionService->sessionHandler->set("user_agent", $_POST['client_info']);

		}

	}


}