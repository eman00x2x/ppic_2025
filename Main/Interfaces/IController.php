<?php

namespace EO\Interfaces;

/**
 * Interface IController
 * This interface defines the basic methods for a controller in the application.
 *
 * @package Main\Controller\Interfaces
 */
interface IController
{
	/**
	 * Index method.
	 * This method handles the default action when a controller is accessed without specifying a method.
	 */
	public function index();

	/**
	 * Add method.
	 * This method handles the action of adding a new record.
	 */
	public function add();

	/**
	 * Edit method.
	 * This method handles the action of editing an existing record.
	 *
	 * @param int $id The unique identifier of the record to be edited.
	 */
	public function edit($id);

	/**
	 * SaveNew method.
	 * This method handles the action of saving a new record.
	 */
	public function saveNew();

	/**
	 * Save method.
	 * This method handles the action of saving an existing record.
	 *
	 * @param int $id The unique identifier of the record to be saved.
	 */
	public function save($id);

	/**
	 * Delete method.
	 * This method handles the action of deleting a record.
	 *
	 * @param int $id The unique identifier of the record to be deleted.
	 */
	public function delete($id = null);

}