<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 9:35 AM
 *
 *
 */
class ChecklistManifest_model extends CI_Model
{
	protected $_table_name = 'checklists';
	protected $_chk_grp_columns = array('id', 'chk_grp_id','chk_grp_code', 'type', 'sort_index', 'key', 'label',
		'BT2', 'BT3', 'BT4', 'BT5', 'BT6', 'BT7',
		'CS1', 'CS2', 'CS3', 'CS4');
	protected $_chk_itm_columns = array('id', 'chk_grp_id', 'chk_itm_id','chk_itm_code','chk_grp_code', 'compliance_if', 'not_compliance_if', 'unknown_if', 'type', 'sort_index', 'key', 'label',
		'BT2', 'BT3', 'BT4', 'BT5', 'BT6', 'BT7',
		'CS1', 'CS2', 'CS3', 'CS4');
	protected $_chk_rm_columns = array('id', 'chk_grp_id', 'chk_itm_id', 'chk_rm_id', 'type', 'sort_index', 'key', 'label',
		'BT2', 'BT3', 'BT4', 'BT5', 'BT6', 'BT7',
		'CS1', 'CS2', 'CS3', 'CS4');

	public static function GUID()
	{
		if (function_exists('com_create_guid') === true) {
			return trim(com_create_guid(), '{}');
		}

		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	public function getYesNoSql(){
		$items = $this->getItems()->result();
		$sql_parts=['_uuid'];
		foreach($items as $item){
			$key_parts=explode('/',$item->key);
			$key = end($key_parts);
			$cIfVal = $item->compliance_if;
			$ncIfVal = $item->not_compliance_if;
			$unIfVal = $item->unknown_if;
			$sql = "CASE ".$key." WHEN '".$cIfVal."' THEN 'yes' "
				//." WHEN '".$ncIfVal."' THEN 'no'"
				//." WHEN '".$unIfVal."' THEN 'unknown'"
				." ELSE 'na' END AS ". $key;
			array_push($sql_parts,$sql);
		}
		return implode(',',$sql_parts);
	}

	public function getAll()
	{
		return $this->db->get($this->getTableName());
	}


	public function getGroups()
	{
		$this->db->select($this->getGroupColumns());
		return $this->db->get_where($this->getTableName(), array('type' => 'chk_grp'));
	}

	public function getItems()
	{
		$this->db->select($this->getItemColumns());
		return $this->db->get_where($this->getTableName(), array('type' => 'chk_itm'));
	}

	public function getRemarks()
	{
		$this->db->select($this->getRemarkColumns());
		return $this->db->get_where($this->getTableName(), array('type' => 'chk_rm'));
	}


	public function getTableName()
	{
		return $this->_table_name;
	}

	public function getGroupColumns(){
		return $this->_chk_grp_columns;
	}

	public function getItemColumns(){
		return $this->_chk_itm_columns;
	}
	public function getRemarkColumns(){
		return $this->_chk_rm_columns;
	}
}