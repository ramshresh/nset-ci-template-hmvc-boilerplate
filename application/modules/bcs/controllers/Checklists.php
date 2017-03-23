<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 3:04 PM
 */
class Checklists extends BaseHmvc_Controller
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
		];
	}


	public function index(){

		$modelChkManifest = new ChecklistManifest_model();

		$groups = $modelChkManifest->getGroups()->result();
		$items = $modelChkManifest->getItems()->result();
		$data['chk_groups'] = $groups;
		$data['chk_items'] = $items;


		$data['page_title']='BCS - Checklists ';
		$data['module']='Building Compliance Survey';
		$data['content_view']='bcs/checklists/index';
		$data['content_view_data']=['module'=>'Building Compliance Survey - Checklists'];


		$data['sub_nav']=$this->sub_nav;
		$this->template->bcs($data);
	}
}