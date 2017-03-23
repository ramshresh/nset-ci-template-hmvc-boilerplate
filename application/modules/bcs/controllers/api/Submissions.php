<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 12:08 PM
 */
class Submissions extends BaseHmvcREST_Controller
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

		$qChkItems = $modelChkManifest->getItems();
		$chkItems = $qChkItems->result();


		$chkItems = $qChkItems->result();

		$names = [];
		$columns = [];
		foreach($chkItems as $item){
			$key=$item->key;
			$keyParts=explode('/',$key);
			$name = end($keyParts);
			$column=[];
			$column['alias']=$name;
			$column['key']=$key;
			array_push($columns, $column);
		}

		$sql_col_select = '';
		foreach($columns as $column){
			$sql_col_select .= '`'.$column['key'].'`'.' AS '. '`'.$column['alias'].'`'.', ';
		}
		// Display all books
		$this->response(
			['sql'=>$sql_col_select], BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function findByUUID($array,$uuid){
		$res= array_filter($array,function($a) use($uuid){
			return $a;
		});

		return (sizeof($res)>0)?:null;
	}

	public function index_pg_get(){

		$model =new Submissions_model();
		$submissions = $model->getSubmissions_postgres();
		$submissions_with_bldg = $model->getSubmissionsWithBldg_postgres();
		$modelChkManifest = new ChecklistManifest_model();

		$qChkItems = $modelChkManifest->getItems();

		$chkItems = $qChkItems->result();

		$passFail_arr = [];

		$yes_arr = [];
		$no_arr = [];
		$tot_arr=[];
		$un_arr = [];
		$na_arr = [];
		foreach($submissions as $s){
			$passFail=[];
			$count_yes = 0;
			$count_no = 0;
			$count_unknown = 0;
			$count_total_applicable = 0;
			$count_total_na = 0;

			foreach($chkItems as $item){
				$key=$item->key;
				$keyParts=explode('/',$key);
				$name = end($keyParts);
				$cIfVal = $item->compliance_if;
				$ncIfVal = $item->not_compliance_if;
				$unIfVal = $item->unknown_if;
				if ($s[$name] ==  $cIfVal){
					$count_yes+=1;
					array_push($yes_arr,$item);
				}

				if ($s[$name] ==  $cIfVal || $s[$name] == $ncIfVal || $s[$name] == $unIfVal){
					$count_total_applicable+=1;
					array_push($yes_arr,$item);
				}else{
					$count_total_na += 1;
					array_push($na_arr,$item);
				}


				if ($s[$name] ==  $unIfVal){
					$count_unknown+=1;
					array_push($un_arr,$item);
				}

				if ($s[$name] ==  $ncIfVal){
					$count_no+=1;
					array_push($no_arr,$item);
				}

			}

			$passFail['_uuid']=$s['_uuid'];
			$passFail['yes']=$count_yes;
			$passFail['no']=$count_no;
			$passFail['unknown']=$count_unknown;
			$passFail['total_applicable']=$count_total_applicable;
			$passFail['count_total_na']=$count_total_na;

			$passFail['total']=$count_total_applicable+$count_total_na;
			$passFail['total_yes_no_unknown']=$count_yes+$count_no+$count_unknown;
			$passFail['pass_percent']=round(($count_yes/$count_total_applicable)*100);

			$passFail['bldg_data']  = $this->findByUUID($submissions_with_bldg,$s['_uuid']);
			$passFail['latitude']= $passFail['bldg_data']['latitude'];
			$passFail['longitude']= $passFail['bldg_data']['longitude'];
			array_push($passFail_arr, $passFail);
		}





		// Display all books
		$this->response(
			//$passFail_arr, BaseHmvcREST_Controller::HTTP_OK
			$submissions_with_bldg, BaseHmvcREST_Controller::HTTP_OK
		);
	}

}