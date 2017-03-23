<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 12:08 PM
 */
class Trainings extends BaseHmvcREST_Controller
{
	public function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
	}

	public function index_get()
	{
		$data=[
			["title"=>"First Mason Training for Masons of Bidur Municipality","start_date"=>"2014-12-12","end_date"=>"2014-12-13",],
		];
		$this->response(
			$data, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function saveone_post()
	{
		$start_date = $this->input->post('start_date');

		if(!isset($start_date)){
			return $this->response(
				["saved"=>false,"errors"=>["start_date"=>"Start Date is required"]], BaseHmvcREST_Controller::HTTP_OK
			);
		}
		// Save;
		return $this->response(
			["saved"=>true], BaseHmvcREST_Controller::HTTP_OK
		);

	}


	public function save_multiple_post()
	{
		return $this->response(
			["post"=>$this->input->post()], BaseHmvcREST_Controller::HTTP_OK
		);

		if(!isset($start_date)){
			return $this->response(
				["saved"=>false,"errors"=>["start_date"=>"Start Date is required"]], BaseHmvcREST_Controller::HTTP_OK
			);
		}
		// Save;
		return $this->response(
			["saved"=>true], BaseHmvcREST_Controller::HTTP_OK
		);

	}
}