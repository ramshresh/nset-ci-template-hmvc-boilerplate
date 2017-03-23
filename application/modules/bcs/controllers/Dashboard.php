<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 4:53 AM
 */
class Dashboard extends BaseHmvc_Controller
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

		$modelChkManifest = new ChecklistManifest_model();

		$checklist_manifests = $modelChkManifest->getAll();
		$data['page_title']='Dashboard - Building Compliance Survey';
		$data['module']='Building Compliance Survey';
		$data['content_view']='bcs/dashboard_v';
		$data['content_view_data']=['module'=>'Building Compliance Survey - Dashboard'];

		$data['checklist_manifest']=$checklist_manifests;

		$data['sub_nav']=$this->sub_nav;
		$this->template->bcs($data);
	}
}