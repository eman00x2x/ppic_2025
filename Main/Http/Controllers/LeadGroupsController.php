<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Auth\Auth;
use EO\Interfaces\IController;
use EO\Services\LeadGroupService;
use EO\Services\LeadService;

class LeadGroupsController extends \EO\Http\BaseController implements IController
{
	protected LeadGroupService $leadGroupService;
	protected LeadService $leadService;

	/**
	 * AccountsController constructor.
	 */
	public function __construct() 
	{
		$this->leadService = new LeadService();
		$this->leadGroupService = new LeadGroupService();
	}

	/**
	 * Returns a list of accounts.
	 * 
	 * @return array The list of accounts.
	 */
	public function index() 
	{
		$this->authorize("view_leads", Auth::user()->account);

		$request = input()->all() ?? [];

		if(!Auth::isAdmin()) {
			$request["account_id"] = Auth::user()->id;
		}

		$data['lead_groups'] = $this->leadGroupService->getLeadGroups($request);

		return \EO\View::set("/authenticated/leads/groups/index.php")->bind(data: $data);
		
	}

	public function add() 
	{
		$this->authorize("add_leads");
		return \EO\View::set("/authenticated/leads/groups/add.php");
	}

	public function edit($id) 
	{
		$data = $this->leadGroupService->getLeadGroup($id);

		$this->authorize("edit_leads", $data);

		return \EO\View::set("/authenticated/leads/groups/edit.php")->bind(data: $data);
	}

	function confirmSelection() 
	{
		$request = input()->all();

		$lead_group_ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];

		$this->authorize($action, Auth::user()->account);

		$options = [
			"delete" => [
				"url" => url("LeadGroupsController@delete"),
				"message" => "You are about to Delete (Permanent) " . count($lead_group_ids) . " group(s). All data related to these group(s) will be transfer to ungrouped leads and this action is ireversible, Are you sure do you want to continue the deletion of these group(s)?"
			]
		];

		$filter['lead_group_id'] = $lead_group_ids;
		if(!Auth::isAdmin()) {
			$filter["account_id"] = Auth::user()->id;
		}

		$lead_groups = $this->leadGroupService->getLeadGroups($filter);

		$data = [
			"lead_groups" => $lead_groups,
			"ids" => implode(",", $lead_group_ids),
			"action" => $action,
			"action_value" => $action_value,
			"url" => $options[$action]['url'],
			"message" => $options[$action]['message']
		];


		return \EO\View::set("/authenticated/leads/groups/confirmSelection.php")->bind(data: $data);
	}

	/**
	 * Save a new account record in the database.
	 * 
	 * @return JSON A JSON containing the status and message of the operation.
	 */
	public function saveNew() 
	{
		$data = input()->all();
		$data['account_id'] = Auth::user()->id;
		$data['created_by'] = Auth::user()->account['full_name'];
		
		try {
			$this->leadGroupService->create($data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully created new lead!");
	}

	/**
	 * Updates an existing account record in the database.
	 * 
	 * @param int $id The ID of the account to update.
	 * @return JSON A JSON containing the status and message of the operation.
	 */
	public function save($id) 
	{
		$request = input()->all();
		$request['updated_by'] = Auth::user()->account['full_name'];

		try {
			$this->leadGroupService->update($id, $request);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully updated lead group!");
	}

	/**
	 * Deletes one or more lead records in the database.
	 *
	 * @param int $ids A comma-separated list of lead IDs to delete.
	 * @return JSON A JSON containing the status and message of the operation.
	 */
	public function delete($ids = null) 
	{
		$request = input()->all();
		$lead_group_ids = explode(",", $request['ids']);

		try {
			$this->leadGroupService->destroyLeadGroups($lead_group_ids);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Lead Groups permanently deleted successfully");
	}

}