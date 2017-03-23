<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/22/2017
 * Time: 7:06 AM
 */
class Submissions_model extends CI_Model
{
	protected $_route = 'uploads/bcs/BCS_DEPLOYED_V1/submissions.csv';
	protected $_path;
	protected $_content;


	public $file;
	public $sql_encoded_file;

	public $district_key = 'bldg_gen_det/bldg_address/bldg_dist';
	public $district_key_short = 'bldg_gen_det/bldg_address/bldg_dist_snm';
	public $vdc_key = 'bldg_gen_det/bldg_address/bldg_vdc_muni';
	public $vdc_key_short = 'bldg_gen_det/bldg_address/vdc_muni_snm';
	public $ward_key = 'bldg_gen_det/bldg_address/bldg_ward';
	public $building_type = 'bldg_gen_det/bg_grp/building_type';
	public $construction_status = 'bldg_gen_det/bg_grp/construction_status';
	public $username_key = 'username';
	public $deviceid = 'deviceid';


	public $survey_date = 'survey_details/survey_date';
	public $d = 'bldg_gen_det/bldg_codes/snm_d';
	public $v = 'bldg_gen_det/bldg_codes/snm_v';
	public $w = 'bldg_gen_det/bldg_codes/snm_w';

	public $recent_date, $oldest_date;
	public $from_date, $to_date;
	public $WHERE_CLAUSE = '';

	function __construct()
	{
		$this->load->helper('qcsv');

		$this->file = FCPATH . $this->_route;
		$this->sql_encoded_file = '"' . $this->file . '"';

		$this->vDistrict = '\"' . $this->district_key . '\"';
		$this->vDistrictShort = '\"' . $this->district_key_short . '\"';
		$this->vVdcMunici = '\"' . $this->vdc_key . '\"';
		$this->vVdcMuniciShort = '\"' . $this->vdc_key_short . '\"';
		$this->vWard = '\"' . $this->ward_key . '\"';
		$this->vBuildingType = '\"' . $this->building_type . '\"';
		$this->vConstructionStatus = '\"' . $this->construction_status . '\"';
		$this->vUsername = '\"' . $this->username_key . '\"';

		$this->vD = '\"' . $this->d . '\"';
		$this->vV = '\"' . $this->v . '\"';
		$this->vW = '\"' . $this->w . '\"';

		$this->vSurveyDate = '\"' . $this->survey_date . '\"';

//Querying Survey Dates
		$recent_date_sql = 'SELECT ' . $this->vSurveyDate . ' FROM ' . $this->file . ' ORDER BY ' . $this->vSurveyDate . ' DESC LIMIT 1';
		$oldest_date_sql = 'SELECT ' . $this->vSurveyDate . ' FROM ' . $this->file . ' ORDER BY ' . $this->vSurveyDate . ' ASC LIMIT 1';

		$this->recent_date = QCSV::execute($recent_date_sql)[0][0];
		$this->oldest_date = QCSV::execute($oldest_date_sql)[0][0];

//From and to Dates
		/*$this->from_date = isset($_GET['from_date']) ? $_GET['from_date'] : $this->oldest_date;
		$this->to_date = isset($_GET['to_date']) ? $_GET['to_date'] : $this->recent_date;*/

//Where clause
		$this->WHERE_CLAUSE = '';
		if (isset($this->from_date) && isset($this->to_date))
			$this->WHERE_CLAUSE .= ' WHERE ' . $this->vSurveyDate . ' BETWEEN \'' . $this->from_date . '\' AND \'' . $this->to_date . '\' ';

	}

	public function getSubmissions()
	{
		$sql = 'SELECT ' . '*' . ' FROM ' . $this->sql_encoded_file . $this->WHERE_CLAUSE;
		return QCSV::execute($sql, true, true);
	}

	public function getSubmissions_postgres()
	{
		$cn = new PDO("pgsql:dbname=bcs;host=localhost", "postgres", "postgres");

		$query=$cn->prepare("select * from survey_chk_items",
			array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)
			);
		$query->execute();

		$submissions = [];
		while($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			array_push($submissions,$row);
		}


		return $submissions;
	}

	public function getSubmissionsWithBldg_postgres(){
		$cn = new PDO("pgsql:dbname=bcs;host=localhost", "postgres", "postgres");

		$query=$cn->prepare("select * from bldg_data");
		$query->execute();

		$submissions = [];
		while($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			array_push($submissions,$row);
		}
		return $submissions;
	}
}