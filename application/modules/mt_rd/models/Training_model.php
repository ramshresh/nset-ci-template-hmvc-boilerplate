<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 3/23/2017
 * Time: 10:36 AM
 */
class Training_model extends CI_Model
{
	protected $tableName = 'mt_rd_trainings';

	public function getAll(){
		$this->db->select('*');
		$this->db->from($this->tableName);
	}

	public function find($id){
		$this->db->select('*');
		$this->db->from($this->tableName);
		$this->db->where('id',$id);
	}
}