<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 4:53 AM
 */
class Home extends BaseHmvc_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->module('template');
	}

	public function index(){

		$data['page_title']='Mason Training';
		$data['module']='Mason Training';
		$data['content_view']='mt/dashboard_v';
		$data['content_view_data']=['module'=>'Mason Training'];
		$this->template->main_template($data);
	}



}