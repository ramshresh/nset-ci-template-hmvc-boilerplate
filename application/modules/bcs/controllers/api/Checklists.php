<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 12:08 PM
 */
class Checklists extends BaseHmvcREST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ChecklistManifest_model');
	}

	public function index_get()
	{
		$modelChkManifest = new ChecklistManifest_model();
		$query = $modelChkManifest->getAll();
		$data=$query->result();
		// Display all books
		$this->response(
			$data, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function groups_get()
	{
		$model = new ChecklistManifest_model();

		$query = $model->getGroups();
		$data=$query->result();
		// Display all books
		$this->response(
			$data, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function items_get()
	{
		$model = new ChecklistManifest_model();
		$query = $model->getItems();
		$data=$query->result();
		// Display all books
		$this->response(
			$data, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function remarks_get()
	{
		$model = new ChecklistManifest_model();
		$query = $model->getRemarks();
		$data=$query->result();
		// Display all books
		$this->response(
			$data, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function sql_yes_no_get()
	{
		$model = new ChecklistManifest_model();
		$this->response(
			$model->getYesNoSql(), BaseHmvcREST_Controller::HTTP_OK
		);
	}
}