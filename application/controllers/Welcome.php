<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends BaseHmvc_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->module('template');
	}
	public function index()
	{
		$data['page_title']='Home';
		$data['content_view']='welcome_message';
		$data['content_view_data']=['module'=>'Home'];
		$this->template->main_template($data);
	}
}
