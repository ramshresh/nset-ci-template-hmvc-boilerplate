<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 4:53 AM
 */
class Dashboard extends BaseHmvc_Controller
{
	private $_sub_nav = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->module('template');

		$this->load->model('MasonTraining_model');
		$this->sub_nav = [['label' => 'Dashboard', 'route' => base_url() . 'mt/dashboard/index'],
			['label' => 'Trainings', 'route' => base_url() . 'mt/dashboard/index'],
			['label' => '+Add Training', 'route' =>base_url() . 'mt/dashboard/add'],
			['label' => '+Add Participants', 'route' =>'#']];
	}

	public function index()
	{

		$mt_model = new MasonTraining_model();

		$mason_trainings = $mt_model->getAll();
		$data['page_title'] = 'Dashboard - Mason Training';
		$data['module'] = 'Mason Training';
		$data['content_view'] = 'mt/dashboard_v';
		$data['content_view_data'] = ['module' => 'Mason Training'];

		$data['mason_trainings'] = $mason_trainings;


		$data['sub_nav'] = $this->sub_nav;

		$this->template->main_template($data);
	}

	public function add()
	{
		$mt_model = new MasonTraining_model();


		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			//throw new Exception('Must be Post Request');
			//show form
			$data['page_title'] = 'Dashboard - Mason Training';
			$data['module'] = 'Mason Training';
			$data['content_view'] = 'mt/insert';
			$data['content_view_data'] = ['module' => 'Mason Training'];

			$data['sub_nav'] = $this->sub_nav;

			$this->template->main_template($data);
		} else {
			$training_uuid = $this->input->post('training_uuid');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$venue = $this->input->post('venue');
			$dist_code = $this->input->post('dist_code');
			$ddvdc_code = $this->input->post('ddvdc_code');
			$ward_no = $this->input->post('ward_no');
			$placename = $this->input->post('placename');
			$latitude = $this->input->post('latitude');
			$longitude = $this->input->post('longitude');
			$latlon_precision = $this->input->post('latlon_precision');

			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['venue'] = $venue;
			$data['dist_code'] = $dist_code;
			$data['ddvdc_code'] = $ddvdc_code;
			$data['ward_no'] = $ward_no;
			$data['placename'] = $placename;
			$data['latitude'] = $latitude;
			$data['longitude'] = $longitude;
			$data['latlon_precision'] = $latlon_precision;
			$data['training_uuid'] = $training_uuid;

			$mt_model->insert($data);
		}

	}


}