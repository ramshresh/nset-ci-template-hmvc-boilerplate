<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 4:53 AM
 */
class Dashboard extends BaseHmvc_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->module('template');
	}

	public function index(){

		$data['page_title']='Dashboard - Orientation';
		$data['module']='Orientation';
		$data['content_view']='ort/dashboard_v';
		$data['content_view_data']=['module'=>'Orientation'];
		$this->template->main_template($data);
	}
}