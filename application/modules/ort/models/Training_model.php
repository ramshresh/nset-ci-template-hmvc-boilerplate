<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 3/23/2017
 * Time: 10:44 AM
 */
class Training_model extends CI_Model implements IDatabaseModel
{
	protected $tableName = 'ort_trainings';

	public function getTableName()
	{
		return $this->tableName;
	}
}