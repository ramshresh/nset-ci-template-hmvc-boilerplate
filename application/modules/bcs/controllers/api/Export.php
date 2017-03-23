<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 12:08 PM
 */
class Export extends BaseHmvcREST_Controller
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
		$this->response(
			['OK'], BaseHmvcREST_Controller::HTTP_OK
		);
	}

	public function recurssiveHelper($node, $gLabel){
		if(isset($node['children'])){
			if(isset($node['label'])){
				$gLabel .= $node['label'];
			}
			foreach($node['children'] as $child){
				$this->recurssiveHelper($child, $gLabel);
			}
		}else{
			return ['group_label'=>$gLabel, 'node'=>$node];
		}
	}

	public function rps_checklist_get(){
		$str = file_get_contents(base_url().'uploads/rps/RPSs.json');
		$json = json_decode($str, true); // decode the JSON into an associative array

		$children = $json['children'];
		$arr = [];

		foreach($children as $child){
			$gLabel='';
			if(isset($child['label'])){
				$gLabel = $child['label'];
			}
			$node=$this->recurssiveHelper($child,$gLabel);
			array_push($arr,$node);
		}

		$checklists_arr=[];
		foreach($arr as $a){
			if(isset($a['node']['choices'])){
				$choices =$a['node']['choices'];
				$counter =0;
				foreach($choices as $c){
					$counter+=1;
					$checklists=[];
					$checklists['group_label']=($counter>1)?'':$a['group_label'];
					$checklists['node_name']=$a['node']['name'];
					$checklists['type']=$a['node']['type'];
					$checklists['choices_name']=$c['name'];
					$checklists['choices_label']=$c['label'];
					array_push($checklists_arr, $checklists);
				}
			}else{
				$checklists=[];
				$checklists['group_label']=$a['group_label'];
				$checklists['node_name']=$a['node']['name'];
				$checklists['type']=$a['node']['type'];
				$checklists['choices_name']='';
				$checklists['choices_label']='';
				array_push($checklists_arr, $checklists);
			}
		}

		try {
			$fp_path = FCPATH.'uploads/rps/RPS-exported.csv';
			$this->outputCsv($fp_path,$checklists_arr);

		}catch(Exception $e){
			throw $e;

		}

		$this->response(
			$checklists_arr, BaseHmvcREST_Controller::HTTP_OK
		);
	}

	/**
	 * Takes in a filename and an array associative data array and outputs a csv file
	 * @param string $fileName
	 * @param array $assocDataArray
	 */
	public function outputCsv($fileName, $assocDataArray)
	{
		ob_clean();
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename=' . $fileName);
		if(isset($assocDataArray['0'])){
			$fp = fopen('php://output', 'w');
			fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
			fputcsv($fp, array_keys($assocDataArray['0']));
			foreach($assocDataArray AS $values){
				fputcsv($fp, $values);
			}
			fclose($fp);
		}
		ob_flush();
	}
}