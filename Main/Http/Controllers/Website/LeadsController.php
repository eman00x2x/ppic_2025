<?php

namespace EO\Http\Controllers\Website;

use EO\Http\BaseController;
use EO\Services\LeadService as LeadService;

class LeadsController extends BaseController
{
	protected LeadService $leadService;

	public function __construct() 
	{
		$this->leadService = new LeadService();
	}
	
	function saveLeads()
	{
		$request = input()->all();

		if($request['security_code'] != $request['generated_security_code']) {
			return $this->handleMessageResponse("Security code does not match", "error", 2);
		}

		$request["created_at"] = DATE_NOW;
		$request["reference"] = json_decode($request['reference'], true);

		if($request['viewing_date'] != "") {
			/** GMAIL Schema to register google calendar */
			$schema[] = "<div itemscope itemtype='http://schema.org/Event'>";
				$schema[] = "<meta itemprop='name' content='Property Viewing'>";
				$schema[] = "<meta itemprop='startDate' content='".date('c', strtotime($request['viewing_date']))."'>";
				$schema[] = "<div itemprop='location' itemscope itemtype='http://schema.org/Place'>";
					$schema[] = "<meta itemprop='name' content='".$request["reference"]['title']."'>";
				$schema[] = "</div>";
			$schema[] = "</div>";

			$request['message'] .= implode("", $schema);
			$request['message'] .= " The client wants to view the property on " . date("F d, Y g:ia", strtotime($request['viewing_date'])) . "";
		}

		try {
			$validated_data = $this->leadService->validateInput($request);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		$this->leadService->create($validated_data);
		$this->leadService->sendLeadToEmail($validated_data);
		
		return $this->handleMessageResponse("Successfully sent the message!");
	}

}