<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 3:04 PM
 */
class Reports extends BaseHmvc_Controller
{
	private $sub_nav = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->module('template');
		$this->load->helper('qcsv');
		$this->load->model('ChecklistManifest_model');
		$this->load->model('ChecklistDefinition_model');
		$this->load->model('Submissions_model');
		$this->sub_nav = [
			['label'=>'Dashboard', 'route'=>base_url().'bcs/dashboard/index'],
			['label'=>'Checklists', 'route'=>base_url().'bcs/checklists/index'],
			['label'=>'Reports', 'route'=>base_url().'bcs/reports/index'],
			['label'=>'Reports(Vue.js)', 'route'=>base_url().'bcs/reports/index_vue'],
			['label'=>'Map', 'route'=>base_url().'bcs/map/index'],
		];
	}


	public function index(){

		$modelChkManifest = new ChecklistManifest_model();
		$modelChkDef = new ChecklistDefinition_model();
		$choices=$modelChkDef->getChoices();

		$groups = $modelChkManifest->getGroups()->result();
		$items = $modelChkManifest->getItems()->result();
		$data['checklists']=[];
		$data['checklists']['chk_groups'] = $groups;
		$data['checklists']['chk_items'] = $items;

		$data['choices']=[];
		$data['choices']['building_types']=$choices['building_types'];
		$data['choices']['construction_statuses']=$choices['building_types'];
		$data['choices']['districts']=$choices['districts'];
		$data['choices']['vdc_municipalities']=$choices['vdc_municipalities'];
		$data['choices']['districts_snm']=$choices['districts_snm'];
		$data['choices']['vdc_municipalities_snm']=$choices['vdc_municipalities_snm'];

		$submission=new Submissions_model();
		$data['submissions']=[];
		$data['submissions']['oldest_date']=$submission->oldest_date;
		$data['submissions']['recent_date']=$submission->recent_date;

		$data['page_title']='BCS - Checklists ';
		$data['module']='Building Compliance Survey';
		$data['content_view']='bcs/reports/index';
		$data['content_view_data']=['module'=>'Building Compliance Survey - Checklists Definitions'];

		$data['sub_nav']=$this->sub_nav;
		$this->template->bcs($data);
	}

	public function index_vue(){

		$modelChkManifest = new ChecklistManifest_model();
		$modelChkDef = new ChecklistDefinition_model();
		$choices=$modelChkDef->getChoices();

		$groups = $modelChkManifest->getGroups()->result();
		$items = $modelChkManifest->getItems()->result();
		$data['checklists']=[];
		$data['checklists']['chk_groups'] = $groups;
		$data['checklists']['chk_items'] = $items;

		$data['choices']=[];
		$data['choices']['building_types']=$choices['building_types'];
		$data['choices']['construction_statuses']=$choices['building_types'];
		$data['choices']['districts']=$choices['districts'];
		$data['choices']['vdc_municipalities']=$choices['vdc_municipalities'];
		$data['choices']['districts_snm']=$choices['districts_snm'];
		$data['choices']['vdc_municipalities_snm']=$choices['vdc_municipalities_snm'];

		$submission=new Submissions_model();
		$data['submissions']=[];
		$data['submissions']['oldest_date']=$submission->oldest_date;
		$data['submissions']['recent_date']=$submission->recent_date;

		$data['page_title']='BCS - Checklists ';
		$data['module']='Building Compliance Survey';
		$data['content_view']='bcs/reports/index_vue';
		$data['content_view_data']=['module'=>'Building Compliance Survey - Checklists Definitions'];

		$data['sub_nav']=$this->sub_nav;
		$this->template->bcs($data);
	}
}