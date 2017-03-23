<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 3/7/2017
 * Time: 3:09 PM
 */
class Map extends  BaseHmvc_Controller
{
	private $sub_nav = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->module('template');
		$this->load->model('ChecklistManifest_model');
		$this->sub_nav = [
			['label'=>'Dashboard', 'route'=>base_url().'bcs/dashboard/index'],
			['label'=>'Checklist', 'route'=>base_url().'bcs/checklists/index'],
			['label'=>'Reports', 'route'=>base_url().'bcs/reports/index'],
			['label'=>'Map', 'route'=>base_url().'bcs/map/index'],
		];
	}

	public function index(){

		$data['page_title']='Dashboard - Building Compliance Survey';
		$data['module']='Building Compliance Survey';
		$data['content_view']='bcs/map/index';
		$data['content_view_data']=['module'=>'Building Compliance Survey - Map'];

		$data['sub_nav']=$this->sub_nav;
		$this->template->bcs($data);
	}

}