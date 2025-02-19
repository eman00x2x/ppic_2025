<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Auth\Auth;
use EO\Interfaces\IController;
use EO\Services\LeadService;
use EO\Services\LeadGroupService;

class LeadsController extends \EO\Http\BaseController implements IController
{
	protected LeadService $leadService;
	protected LeadGroupService $leadGroupService;

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

		$data['groups'] = $this->leadGroupService->getLeadGroups([
			"account_id" => Auth::user()->id,
			"rows" => 1000
		]);

		$data['leads'] = $this->leadService->getLeads($request);

		$data['sources'] = $this->leadService->sources();

		return \EO\View::set("/authenticated/leads/index.php")->bind(data: $data);
		
	}

	public function add() 
	{
		$this->authorize("add_leads");

		$data['sources'] = $this->leadService->sources();

		return \EO\View::set("/authenticated/leads/add.php")->bind(data: $data);
	}

	public function edit($id) 
	{
		$data = $this->leadService->getLead($id);

		$this->authorize("edit_leads", $data);
		$data['sources'] = $this->leadService->sources();

		return \EO\View::set("/authenticated/leads/edit.php")->bind(data: $data);
	}

	public function view($id) 
	{
		$data = $this->leadService->getLead($id);

		$this->authorize("view_leads", $data);
		$data['sources'] = $this->leadService->sources();

		return \EO\View::set("/authenticated/leads/view.php")->bind(data: $data);
	}

	function confirmSelection() 
	{
		$request = input()->all();

		$lead_ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];

		$group_name = null;
		if($action === "move_to_group") {
			[$group_id, $group_name] = explode("_", $action_value);
		}

		$options = [
			"set_source" => [
				"url" => url("leads.updateSource"),
				"message" => "You are about to change the source of " . count($lead_ids) . " leads(s) to $action_value. Are you sure do you want to continue the action?"
			],
			"move_to_group" => [
				"url" => url("leads.updateGroup"),
				"message" => "You are about to move " . count($lead_ids) . " leads(s) to $group_name. Are you sure do you want to continue the action?"
			],
			"delete" => [
				"url" => url("leads.delete"),
				"message" => "You are about to Delete (Permanent) " . count($lead_ids) . " leads(s). All data related to these leads(s) will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of these leads(s)?"
			]
		];

		$filter['lead_id'] = $lead_ids;
		if(!Auth::isAdmin()) {
			$filter["account_id"] = Auth::user()->id;
		}

		$leads = $this->leadService->getLeads($filter);

		$data = [
			"leads" => $leads,
			"ids" => implode(",", $lead_ids),
			"action" => $action,
			"action_value" => $action_value,
			"url" => $options[$action]['url'],
			"message" => $options[$action]['message']
		];


		return \EO\View::set("/authenticated/leads/confirmSelection.php")->bind(data: $data);
	}

	/**
	 * Save a new account record in the database.
	 * 
	 * @return JSON A JSON containing the status and message of the operation.
	 */
	public function saveNew() 
	{
		$data = input()->all();
		$data['created_by'] = Auth::user()->account['full_name'];
		
		try {
			$this->leadService->create($data);
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
			$this->leadService->update($id, $request);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully updated lead!");
	}

	/**
	 * Updates the source of one or more leads.
	 * 
	 * @param array $request The request data containing the IDs of the leads and the source to update them to.
	 * @return JSON A JSON containing the status and message of the operation.
	 */
	public function updateSource()
	{
		$request = input()->all();
		$lead_ids = explode(",", $request['ids']);
		$source = $request['action_value'];

		try {
			$this->leadService->updateSource($lead_ids, $source);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully moved leads to source $source!");
	}

	public function updateGroup()
	{
		$request = input()->all();
		$lead_ids = explode(",", $request['ids']);
		$action_value = $request['action_value'];

		[$group_id, $group_name] = explode("_", $action_value);

		try {
			$this->leadService->updateGroup($lead_ids, $group_id);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully moved leads to group $group_name!");
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
		$lead_ids = explode(",", $request['ids']);

		try {
			$this->leadService->destroyLeads($lead_ids);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Leads permanently deleted successfully");
	}


	public function download(): void
	{
		$this->authorize("download_leads", Auth::user()->account);
		$account_id = Auth::isAdmin() ? null : Auth::user()->id;
		$this->leadService->downloadData($account_id);
	}

}