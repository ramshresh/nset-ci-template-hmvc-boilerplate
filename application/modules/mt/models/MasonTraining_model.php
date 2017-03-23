<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 9:35 AM
 *
 *
 */
class MasonTraining_model extends CI_Model
{
	public $training_id, $start_date, $end_date, $venue, $dist_code, $ddvdc_code, $ward_no, $placename, $latitude, $longitude, $latlon_precision, $training_uuid;

	protected $_table_name = 'mt_training';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('guid');
	}

	public function getAll()
	{
		$query = $this->db->get($this->getTableName());
		$data = [];
		foreach ($query->result() as $row) {
			echo $row->title;
			array_push($data, $row);
		}

		return $data;
	}

	public function insert($data)
	{
		$this->training_uuid = isset($data['training_uuid'])?$data['training_uuid']:GUID::generate(); // please read the below note

		if (!isset($_POST)) {
			throw new Exception('Must be Post Request');
		}
		$this->start_date = $data['start_date'];
		$this->end_date = $data['end_date'];
		$this->venue = $data['venue'];
		$this->dist_code = $data['dist_code'];
		$this->ddvdc_code = $data['ddvdc_code'];
		$this->ward_no = $data['ward_no'];
		$this->placename = $data['placename'];
		$this->latitude = $data['latitude'];
		$this->longitude = $data['longitude'];
		$this->latlon_precision = $data['latlon_precision'];

		$this->db->insert($this->getTableName(), $this);
	}

	public function getTableName(){
		return $this->_table_name;
	}
}