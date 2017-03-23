<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 5:04 AM
 */
class Template extends BaseHmvc_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main_template($data=null){
		$this->load->view('main',$data);
	}

	public function bcs($data=null){
		$this->load->view('bcs/main',$data);
	}

}