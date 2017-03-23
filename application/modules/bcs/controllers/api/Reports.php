<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 12:08 PM
 */
class Reports extends BaseHmvcREST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ChecklistManifest_model');
		$this->load->model('ChecklistDefinition_model');
		$this->load->model('Submissions_model');
		$this->load->helper('qcsv');
	}

	public function index_get()
	{
		$modelChkManifest = new ChecklistManifest_model();
		$query = $modelChkManifest->getAll();
		$data = $query->result();
		// Display all books
		$this->response(
			$data, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function master_data_get()
	{
		$mChkManifest = new ChecklistManifest_model();
		$mChkDef = new ChecklistDefinition_model();


		$choices = $mChkDef->getChoices();
		$bTypes = $choices['building_types'];
		$cStatuses = $choices['construction_statuses'];

		$data_count=0;
		$total_count=0;

		$chk_groups = $mChkManifest->getGroups()->result_array();
		$chk_items = $mChkManifest->getItems()->result_array();

		$model = new Submissions_model();
		$vCSVFILE = '"' . $model->file . '"';
		$model->from_date = (isset($_GET['from_date']) && $_GET['from_date'] != '') ? $_GET['from_date'] : $model->oldest_date;
		$model->to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : $model->recent_date;


		//Where clause
		$WHERE_CLAUSE = '';
		if (isset($model->from_date) && isset($model->to_date))
			$WHERE_CLAUSE .= ' WHERE ' . $model->vSurveyDate . ' BETWEEN \'' . $model->from_date . '\' AND \'' . $model->to_date . '\' ';

		$qD = (isset($_GET['districts']) && !empty($_GET['districts'])) ? $_GET['districts'] : '';
		$qV = (isset($_GET['vdc_municipalities']) && !empty($_GET['vdc_municipalities'])) ? $_GET['vdc_municipalities'] : '';


		$wD = '';
		$wV = '';


		if (is_array($qD) && !empty($qD)) {
			array_walk($qD, function (&$qD) {
				$qD = '\'' . $qD . '\'';
			});
			$wD_and = ' AND ' . $model->vDistrict . ' IN(' . implode(',', $qD) . ')';
			$wD_where = ' WHERE ' . $model->vDistrict . ' IN(' . implode(',', $qD) . ')';
			$WHERE_CLAUSE = ($WHERE_CLAUSE == '') ? $wD_where : $WHERE_CLAUSE . $wD_and;
		}
		if (is_array($qV) && !empty($qV)) {
			array_walk($qV, function (&$qV) {
				$qV = '\'' . $qV . '\'';
			});
			$wV_and = ' AND ' . $model->vVdcMunici . ' IN(' . implode(',', $qV) . ')';
			$wV_where = ' WHERE ' . $model->vVdcMunici . ' IN(' . implode(',', $qV) . ')';
			$WHERE_CLAUSE = ($WHERE_CLAUSE == '') ? $wV_where : $WHERE_CLAUSE . $wV_and;
		}


		$result_columns = ["chk_grp", "chk_item", "district", "vdc_muni", "building_type", "construction_status", "yes", "no", "unknown", "not_applicable"];
		$labels = [];
		$graph_data_array = [];
		$sqlUnion = "";
		$counter = 0;

		$total_submissions = QCSV::execute('SELECT COUNT(*) FROM ' . $vCSVFILE . ' ' . $WHERE_CLAUSE, false, false)[0][0];


		$sqlUnion = "";
		$sqlUnionArr = [];
		$sqlLen = 0;

		if ($total_submissions > 0) {
			foreach ($chk_groups as $chk_grp_item) {
				$graph_data_item = [];
				$ckh_grp_key = $chk_grp_item['key'];
				$chk_grp_id = $chk_grp_item['chk_grp_id'];

				$chk_grp_chk_items = array_filter($chk_items, function ($el) use ($chk_grp_id) {
					return $el['chk_grp_id'] == $chk_grp_id;
				});

				$ckh_grp_key_title = $chk_grp_item['label'];
				$labels[$ckh_grp_key] = $chk_grp_item['label'];

				foreach ($chk_grp_chk_items as $chk_item) {
					$chk_itm_id = $chk_item['chk_itm_id'];
					$chk_item_key = $chk_item['key'];
					$cif_val = $chk_item['compliance_if'];
					$ncif_val = $chk_item['not_compliance_if'];
					$unkif_val = $chk_item['unknown_if'];
					$naif_val = '\'n/a\'';//$chk_item['na_compliance_if'];

					$labels[$chk_item_key] = $chk_item['label'];

					//q -H -d , "SELECT 'building_technical_details/s_s/s_t_l', 'building_technical_details/s_s/s_t_l',SUM(CASE WHEN \"building_technical_details/s_s/s_t_l\" = 1  THEN 1 ELSE 0 END) AS 'yes', SUM(CASE WHEN \"building_technical_details/s_s/s_t_l\" = 2  THEN 1 ELSE 0 END) AS 'no' , SUM(CASE WHEN \"building_technical_details/s_s/s_t_l\" IS NULL  THEN 1 ELSE 0 END) AS 'n/a' FROM "E:\submissions.csv" AS s
					//           SELECT 'building_technical_details/s_s','building_technical_details/s_s/p_g_f_r_a', SUM(CASE \"building_technical_details/s_s/p_g_f_r_a\" = 1 THEN 1 ELSE 0 END) as 'yes' , SUM(CASE \"building_technical_details/s_s/p_g_f_r_a\" = 2 THEN 1 ELSE 0 END) as 'no' , SUM(CASE \"building_technical_details/s_s/p_g_f_r_a\" IS NULL THEN 1 ELSE 0 END) as 'n/a' FROM "C:/xampp/htdocs/bg/cs/storage/xforms/BCS_DEPLOYED_V1/submissions.csv" WHERE 'building_technical_details/s_s/p_g_f_r_a' = 1
					$chk_item_key_sql_escaped = '\"' . $chk_item_key . '\"';

					$tSql = 'select '
						. '\'' . $ckh_grp_key . '\'' . ' as \'chk_grp\' '
						. ',' . $chk_grp_id . ' as \'chk_grp_id\' '
						. ',' . '\'' . $chk_item_key . '\'' . ' as \'chk_item\' '
						. ',' . $chk_itm_id . ' as \'chk_itm_id\' '
						. ',' . $model->vBuildingType . ' as building_type'
						. ',' . $model->vConstructionStatus . ' as construction_status'
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $cif_val . ' THEN 1 ELSE 0 END) as \'yes\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $ncif_val . ' THEN 1 ELSE 0 END) as \'no\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $unkif_val . ' THEN 1 ELSE 0 END) as \'unknown\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $naif_val . ' THEN 1 ELSE 0 END) as \'not_applicable\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' IN( ' . implode(',', [$cif_val, $ncif_val, $unkif_val]) . ') THEN 1 ELSE 0 END) as \'applicable\' '
						. ' from '
						. $model->sql_encoded_file
						. $WHERE_CLAUSE
						. ' GROUP BY '
						. $model->vConstructionStatus
						. ',' . $model->vBuildingType
						. ',' . '\'chk_item\' '
						. ',' . '\'chk_grp\' ';


					//$sqlUnion = ($sqlUnion == '') ? $tSql : $sqlUnion . ' UNION ' . $tSql;
					//------------------------------
					$sqlLen = strlen($sqlUnion);
					if ($sqlLen <= 7000) {
						$sqlUnion = ($sqlUnion == '') ? $tSql : $sqlUnion . ' UNION ' . $tSql;
					} else {
						array_push($sqlUnionArr, $sqlUnion);
						$sqlLen = 0;
						$sqlUnion = '';
					}
					//------------------------------
				}
			}

			//print_r(sizeof($sqlUnionArr));echo'<hr>';print_r($sqlUnionArr[0]);exit();
			//$graph_data_array = QCSV::execute($sqlUnion, false, false);

			$graph_data_array = [];
			foreach ($sqlUnionArr as $sql) {
				$d = QCSV::execute($sql, true, true);
				$graph_data_array = array_merge($graph_data_array, $d);
			}


			// Count by Btype and Cstatus
			$count_Sql = 'select '
				. $model->vBuildingType . ' as building_type'
				. ', ' . $model->vConstructionStatus . ' as construction_status'
				. ',' . ' COUNT(*) AS count'
				. ' from '
				. $model->sql_encoded_file
				. $WHERE_CLAUSE
				. ' GROUP BY '
				. $model->vConstructionStatus
				. ', ' . $model->vBuildingType
				. ' ORDER BY '
				. $model->vBuildingType
				. ', ' . $model->vConstructionStatus;

			$data_count = QCSV::execute($count_Sql, true, true);

			$total_count = 0;




			$totals_count = [];
			$totals_count['building_type'] = [];
			$totals_count['construction_status'] = [];


			foreach ($bTypes as $bType) {
				$bTypeName = $bType['name'];
				$fD = array_filter($data_count, function ($el) use ($bTypeName) {
					return $el['building_type'] == $bTypeName;
				});


				foreach ($fD as $d) {
					if (isset($totals_count['building_type'][$bTypeName])){
						$totals_count['building_type'][$bTypeName] += $d['count'];
					}

				}
			}

			foreach ($cStatuses as $c) {
				$cName = $c['name'];
				$fD = array_filter($data_count, function ($el) use ($cName) {
					return $el['construction_status'] == $cName;
				});
				foreach ($fD as $d) {
					if (isset($totals_count['construction_status'][$cName])) {
						$totals_count['construction_status'][$cName] += $d['count'];
					}
				}
			}


			foreach ($data_count as $d) {
				$total_count += $d['count'];
			}

			//Saving to CSV
			/**
			 * @see : http://stackoverflow.com/questions/16391528/query-mysql-and-export-data-as-csv-in-php
			 */
			//{{{
			/*$result = mysqli_query($con, 'SELECT * FROM table');
			$row = QCSV::execute($sqlUnion, true, true);

			$fp = fopen('file.csv', 'w');

			foreach ($row as $val) {
				fputcsv($fp, $val);
			}
			fclose($fp);*/

			//$csvData = shell_exec(QCSV::getClExp($sqlUnion, ',', true));
			$csvData = '';

			foreach ($sqlUnionArr as $index=>$sql) {
				if($index==0){
					//First query with header
					$d = shell_exec(QCSV::getClExp($sql, ',', true));

				}else{
					//Other query without header
					$d = shell_exec(QCSV::getClExp($sql, ',', false));
				}
				$csvData .= $d;
			}

			//$fp = tmpfile();
			try {
				$fp_path = FCPATH . 'uploads/bcs/BCS_DEPLOYED_V1/' . 'new_submissions_btype_by_chk_grp.csv';
				$fp = fopen($fp_path, 'w');
				fwrite($fp, $csvData);
				rewind($fp); //rewind to process CSV
				fclose($fp);
			}catch(Exception $e){
				throw $e;

			}
			//}}}



		} else {
			$graph_data_array = [];
		}


		$response=[
			'data'=>$graph_data_array,
			'building_types'=>$bTypes,
			'data_count' => $data_count,
			'construction_statuses'=>$cStatuses,
			'data_count_total' => $data_count,
			'dataCountTotal'=>$total_count,
			'coverage_district' => $qD,
			'coverage_vdc' => $qV,
			'dates' => ['oldest' => $model->oldest_date, 'recent' => $model->recent_date, 'from_date' => $model->from_date, 'to_date' => $model->to_date],
		];

		//$fp_path_response = FCPATH . 'uploads/bcs/BCS_DEPLOYED_V1/api/bcs/reports/master-data.json';
		//file_put_contents($fp_path_response, json_encode($response));

		$this->response(
		$response	, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function saveMasterData(){
		$mChkManifest = new ChecklistManifest_model();
		$mChkDef = new ChecklistDefinition_model();


		$choices = $mChkDef->getChoices();
		$bTypes = $choices['building_types'];
		$cStatuses = $choices['construction_statuses'];

		$data_count=0;
		$total_count=0;

		$chk_groups = $mChkManifest->getGroups()->result_array();
		$chk_items = $mChkManifest->getItems()->result_array();

		$model = new Submissions_model();
		$vCSVFILE = '"' . $model->file . '"';
		$model->from_date = (isset($_GET['from_date']) && $_GET['from_date'] != '') ? $_GET['from_date'] : $model->oldest_date;
		$model->to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : $model->recent_date;


		//Where clause
		$WHERE_CLAUSE = '';
		if (isset($model->from_date) && isset($model->to_date))
			$WHERE_CLAUSE .= ' WHERE ' . $model->vSurveyDate . ' BETWEEN \'' . $model->from_date . '\' AND \'' . $model->to_date . '\' ';

		$qD = (isset($_GET['districts']) && !empty($_GET['districts'])) ? $_GET['districts'] : '';
		$qV = (isset($_GET['vdc_municipalities']) && !empty($_GET['vdc_municipalities'])) ? $_GET['vdc_municipalities'] : '';


		$wD = '';
		$wV = '';


		if (is_array($qD) && !empty($qD)) {
			array_walk($qD, function (&$qD) {
				$qD = '\'' . $qD . '\'';
			});
			$wD_and = ' AND ' . $model->vDistrict . ' IN(' . implode(',', $qD) . ')';
			$wD_where = ' WHERE ' . $model->vDistrict . ' IN(' . implode(',', $qD) . ')';
			$WHERE_CLAUSE = ($WHERE_CLAUSE == '') ? $wD_where : $WHERE_CLAUSE . $wD_and;
		}
		if (is_array($qV) && !empty($qV)) {
			array_walk($qV, function (&$qV) {
				$qV = '\'' . $qV . '\'';
			});
			$wV_and = ' AND ' . $model->vVdcMunici . ' IN(' . implode(',', $qV) . ')';
			$wV_where = ' WHERE ' . $model->vVdcMunici . ' IN(' . implode(',', $qV) . ')';
			$WHERE_CLAUSE = ($WHERE_CLAUSE == '') ? $wV_where : $WHERE_CLAUSE . $wV_and;
		}


		$result_columns = ["chk_grp", "chk_item", "district", "vdc_muni", "building_type", "construction_status", "yes", "no", "unknown", "not_applicable"];
		$labels = [];
		$graph_data_array = [];
		$sqlUnion = "";
		$counter = 0;

		$total_submissions = QCSV::execute('SELECT COUNT(*) FROM ' . $vCSVFILE . ' ' . $WHERE_CLAUSE, false, false)[0][0];


		$sqlUnion = "";
		$sqlUnionArr = [];
		$sqlLen = 0;

		if ($total_submissions > 0) {
			foreach ($chk_groups as $chk_grp_item) {
				$graph_data_item = [];
				$ckh_grp_key = $chk_grp_item['key'];
				$chk_grp_id = $chk_grp_item['chk_grp_id'];

				$chk_grp_chk_items = array_filter($chk_items, function ($el) use ($chk_grp_id) {
					return $el['chk_grp_id'] == $chk_grp_id;
				});

				$ckh_grp_key_title = $chk_grp_item['label'];
				$labels[$ckh_grp_key] = $chk_grp_item['label'];

				foreach ($chk_grp_chk_items as $chk_item) {
					$chk_itm_id = $chk_item['chk_itm_id'];
					$chk_item_key = $chk_item['key'];
					$cif_val = $chk_item['compliance_if'];
					$ncif_val = $chk_item['not_compliance_if'];
					$unkif_val = $chk_item['unknown_if'];
					$naif_val = '\'n/a\'';//$chk_item['na_compliance_if'];

					$labels[$chk_item_key] = $chk_item['label'];

					//q -H -d , "SELECT 'building_technical_details/s_s/s_t_l', 'building_technical_details/s_s/s_t_l',SUM(CASE WHEN \"building_technical_details/s_s/s_t_l\" = 1  THEN 1 ELSE 0 END) AS 'yes', SUM(CASE WHEN \"building_technical_details/s_s/s_t_l\" = 2  THEN 1 ELSE 0 END) AS 'no' , SUM(CASE WHEN \"building_technical_details/s_s/s_t_l\" IS NULL  THEN 1 ELSE 0 END) AS 'n/a' FROM "E:\submissions.csv" AS s
					//           SELECT 'building_technical_details/s_s','building_technical_details/s_s/p_g_f_r_a', SUM(CASE \"building_technical_details/s_s/p_g_f_r_a\" = 1 THEN 1 ELSE 0 END) as 'yes' , SUM(CASE \"building_technical_details/s_s/p_g_f_r_a\" = 2 THEN 1 ELSE 0 END) as 'no' , SUM(CASE \"building_technical_details/s_s/p_g_f_r_a\" IS NULL THEN 1 ELSE 0 END) as 'n/a' FROM "C:/xampp/htdocs/bg/cs/storage/xforms/BCS_DEPLOYED_V1/submissions.csv" WHERE 'building_technical_details/s_s/p_g_f_r_a' = 1
					$chk_item_key_sql_escaped = '\"' . $chk_item_key . '\"';

					$tSql = 'select '
						. $model->vDistrict . ' as district'
						. ',' . $model->vVdcMunici . ' as vdc'
						. ','. '\'' . $ckh_grp_key . '\'' . ' as \'chk_grp\' '
						. ',' . $chk_grp_id . ' as \'chk_grp_id\' '
						. ',' . '\'' . $chk_item_key . '\'' . ' as \'chk_item\' '
						. ',' . $chk_itm_id . ' as \'chk_itm_id\' '
						. ',' . $model->vBuildingType . ' as building_type'
						. ',' . $model->vConstructionStatus . ' as construction_status'
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $cif_val . ' THEN 1 ELSE 0 END) as \'yes\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $ncif_val . ' THEN 1 ELSE 0 END) as \'no\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $unkif_val . ' THEN 1 ELSE 0 END) as \'unknown\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' = ' . $naif_val . ' THEN 1 ELSE 0 END) as \'not_applicable\' '
						. ', SUM(CASE WHEN ' . $chk_item_key_sql_escaped . ' IN( ' . implode(',', [$cif_val, $ncif_val, $unkif_val]) . ') THEN 1 ELSE 0 END) as \'applicable\' '
						. ' from '
						. $model->sql_encoded_file
						. $WHERE_CLAUSE
						. ' GROUP BY '
						. $model->vConstructionStatus
						. ',' . $model->vBuildingType
						. ',' . '\'chk_item\' '
						. ',' . '\'chk_grp\' '
						. ',' . $model->vVdcMunici
						. ',' . $model->vDistrict;


					//$sqlUnion = ($sqlUnion == '') ? $tSql : $sqlUnion . ' UNION ' . $tSql;
					//------------------------------
					$sqlLen = strlen($sqlUnion);
					if ($sqlLen <= 7000) {
						$sqlUnion = ($sqlUnion == '') ? $tSql : $sqlUnion . ' UNION ' . $tSql;
					} else {
						array_push($sqlUnionArr, $sqlUnion);
						$sqlLen = 0;
						$sqlUnion = '';
					}
					//------------------------------
				}
			}

			//print_r(sizeof($sqlUnionArr));echo'<hr>';print_r($sqlUnionArr[0]);exit();
			//$graph_data_array = QCSV::execute($sqlUnion, false, false);

			$graph_data_array = [];
			foreach ($sqlUnionArr as $sql) {
				$d = QCSV::execute($sql, true, true);
				$graph_data_array = array_merge($graph_data_array, $d);
			}


			// Count by Btype and Cstatus
			$count_Sql = 'select '
				. $model->vBuildingType . ' as building_type'
				. ', ' . $model->vConstructionStatus . ' as construction_status'
				. ',' . ' COUNT(*) AS count'
				. ' from '
				. $model->sql_encoded_file
				. $WHERE_CLAUSE
				. ' GROUP BY '
				. $model->vConstructionStatus
				. ', ' . $model->vBuildingType
				. ' ORDER BY '
				. $model->vBuildingType
				. ', ' . $model->vConstructionStatus;

			$data_count = QCSV::execute($count_Sql, true, true);

			$total_count = 0;




			$totals_count = [];
			$totals_count['building_type'] = [];
			$totals_count['construction_status'] = [];


			foreach ($bTypes as $bType) {
				$bTypeName = $bType['name'];
				$fD = array_filter($data_count, function ($el) use ($bTypeName) {
					return $el['building_type'] == $bTypeName;
				});


				foreach ($fD as $d) {
					if (isset($totals_count['building_type'][$bTypeName])){
						$totals_count['building_type'][$bTypeName] += $d['count'];
					}

				}
			}

			foreach ($cStatuses as $c) {
				$cName = $c['name'];
				$fD = array_filter($data_count, function ($el) use ($cName) {
					return $el['construction_status'] == $cName;
				});
				foreach ($fD as $d) {
					if (isset($totals_count['construction_status'][$cName])) {
						$totals_count['construction_status'][$cName] += $d['count'];
					}
				}
			}


			foreach ($data_count as $d) {
				$total_count += $d['count'];
			}

			//Saving to CSV
			/**
			 * @see : http://stackoverflow.com/questions/16391528/query-mysql-and-export-data-as-csv-in-php
			 */
			//{{{
			/*$result = mysqli_query($con, 'SELECT * FROM table');
			$row = QCSV::execute($sqlUnion, true, true);

			$fp = fopen('file.csv', 'w');

			foreach ($row as $val) {
				fputcsv($fp, $val);
			}
			fclose($fp);*/

			//$csvData = shell_exec(QCSV::getClExp($sqlUnion, ',', true));
			$csvData = '';

			foreach ($sqlUnionArr as $index=>$sql) {
				if($index==0){
					//First query with header
					$d = shell_exec(QCSV::getClExp($sql, ',', true));

				}else{
					//Other query without header
					$d = shell_exec(QCSV::getClExp($sql, ',', false));
				}
				$csvData .= $d;
			}

			//$fp = tmpfile();
			$fp_path = FCPATH . 'uploads/bcs/BCS_DEPLOYED_V1/' . 'submissions_btype_by_chk_grp.csv';
			$fp = fopen($fp_path, 'w');
			fwrite($fp, $csvData);
			rewind($fp); //rewind to process CSV
			fclose($fp);
			//}}}



		} else {
			$graph_data_array = [];
		}


		$response=[
			'data'=>$graph_data_array,
			'building_types'=>$bTypes,
			'data_count' => $data_count,
			'construction_statuses'=>$cStatuses,
			'data_count_total' => $data_count,
			'dataCountTotal'=>$total_count,
			'coverage_district' => $qD,
			'coverage_vdc' => $qV,
			'dates' => ['oldest' => $model->oldest_date, 'recent' => $model->recent_date, 'from_date' => $model->from_date, 'to_date' => $model->to_date],
		];

		$fp_path_response = FCPATH . 'uploads/bcs/BCS_DEPLOYED_V1/api/bcs/reports/master-data-dist-vdc.json';
		file_put_contents($fp_path_response, json_encode($response));

		$this->response(
			$response	, BaseHmvcREST_Controller::HTTP_OK
		);
	}


	public function btypes_get(){
		$mChkManifest = new ChecklistManifest_model();
		$mChkDef = new ChecklistDefinition_model();


		$choices = $mChkDef->getChoices();
		$bTypes = $choices['building_types'];
		$cStatuses = $choices['construction_statuses'];

		$data_count=0;
		$total_count=0;

		$chk_groups = $mChkManifest->getGroups()->result_array();
		$chk_items = $mChkManifest->getItems()->result_array();

		$model = new Submissions_model();
		$vCSVFILE = '"' . $model->file . '"';
		$model->from_date = (isset($_GET['from_date']) && $_GET['from_date'] != '') ? $_GET['from_date'] : $model->oldest_date;
		$model->to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : $model->recent_date;


		//Where clause
		$WHERE_CLAUSE = '';
		if (isset($model->from_date) && isset($model->to_date))
			$WHERE_CLAUSE .= ' WHERE ' . $model->vSurveyDate . ' BETWEEN \'' . $model->from_date . '\' AND \'' . $model->to_date . '\' ';

		$qD = (isset($_GET['districts']) && !empty($_GET['districts'])) ? $_GET['districts'] : '';
		$qV = (isset($_GET['vdc_municipalities']) && !empty($_GET['vdc_municipalities'])) ? $_GET['vdc_municipalities'] : '';


		$wD = '';
		$wV = '';


		if (is_array($qD) && !empty($qD)) {
			array_walk($qD, function (&$qD) {
				$qD = '\'' . $qD . '\'';
			});
			$wD_and = ' AND ' . $model->vDistrict . ' IN(' . implode(',', $qD) . ')';
			$wD_where = ' WHERE ' . $model->vDistrict . ' IN(' . implode(',', $qD) . ')';
			$WHERE_CLAUSE = ($WHERE_CLAUSE == '') ? $wD_where : $WHERE_CLAUSE . $wD_and;
		}
		if (is_array($qV) && !empty($qV)) {
			array_walk($qV, function (&$qV) {
				$qV = '\'' . $qV . '\'';
			});
			$wV_and = ' AND ' . $model->vVdcMunici . ' IN(' . implode(',', $qV) . ')';
			$wV_where = ' WHERE ' . $model->vVdcMunici . ' IN(' . implode(',', $qV) . ')';
			$WHERE_CLAUSE = ($WHERE_CLAUSE == '') ? $wV_where : $WHERE_CLAUSE . $wV_and;
		}


		$result_columns = ["chk_grp", "chk_item", "district", "vdc_muni", "building_type", "construction_status", "yes", "no", "unknown", "not_applicable"];
		$labels = [];
		$graph_data_array = [];
		$sqlUnion = "";
		$counter = 0;

		$total_submissions = QCSV::execute('SELECT COUNT(*) FROM ' . $vCSVFILE . ' ' . $WHERE_CLAUSE, false, false)[0][0];


		$sqlUnion = "";
		$sqlUnionArr = [];
		$sqlLen = 0;

		if ($total_submissions > 0) {
			$sql = 'select '
				. $model->vBuildingType . ' as building_type'
				. ', ' . $model->vConstructionStatus . ' as construction_status'
				. ',' . ' COUNT(*) AS count'
				. ' from '
				. $model->sql_encoded_file
				. $WHERE_CLAUSE
				. ' GROUP BY '
				. $model->vConstructionStatus
				. ', ' . $model->vBuildingType
				. ' ORDER BY '
				. $model->vBuildingType
				. ', ' . $model->vConstructionStatus;

			$data_count = QCSV::execute($sql, true, true);


			//Saving to CSV
			/**
			 * @see : http://stackoverflow.com/questions/16391528/query-mysql-and-export-data-as-csv-in-php
			 */
			//{{{
			/*$result = mysqli_query($con, 'SELECT * FROM table');
			$row = QCSV::execute($sqlUnion, true, true);

			$fp = fopen('file.csv', 'w');

			foreach ($row as $val) {
				fputcsv($fp, $val);
			}
			fclose($fp);*/

			$csvData = shell_exec(QCSV::getClExp($sql, ',', true));
			//$fp = tmpfile();
			try {
				$fp_path = FCPATH . 'uploads/bcs/BCS_DEPLOYED_V1/' . 'count_btype_Cstatus.csv';
				$fp = fopen($fp_path, 'w');
				fwrite($fp, $csvData);
				rewind($fp); //rewind to process CSV
				fclose($fp);
			}catch(Exception $e){
				throw $e;

			}
			//}}}



		} else {
			$graph_data_array = [];
		}


		$response=[
			'data'=>$data_count,
			'building_types'=>$bTypes,
			'data_count' => $data_count,
			'construction_statuses'=>$cStatuses,
			'data_count_total' => $data_count,
			'dataCountTotal'=>$total_count,
			'coverage_district' => $qD,
			'coverage_vdc' => $qV,
			'dates' => ['oldest' => $model->oldest_date, 'recent' => $model->recent_date, 'from_date' => $model->from_date, 'to_date' => $model->to_date],
		];

		//$fp_path_response = FCPATH . 'uploads/bcs/BCS_DEPLOYED_V1/api/bcs/reports/master-data.json';
		//file_put_contents($fp_path_response, json_encode($response));

		$this->response(
			$response	, BaseHmvcREST_Controller::HTTP_OK
		);
	}
}