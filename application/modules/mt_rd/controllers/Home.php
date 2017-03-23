<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 3/23/2017
 * Time: 10:32 AM
 */
class Home extends BaseHmvc_Controller
{
	public  function index(){
		$this->load->view('mt_rd/home/index');
	}
}