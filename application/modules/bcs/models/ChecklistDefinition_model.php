<?php

/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/22/2017
 * Time: 4:45 AM
 */
class ChecklistDefinition_model
{
	protected $_route='uploads/bcs/BCS_DEPLOYED_V1/xlsform.json';
	protected $_path;
	protected $_content;

	protected $route=null;
	public function __construct($filename=null)
	{
		$this->route=(isset($filename))?$filename:$this->_route;
		$this->_path=base_url().$this->route;
		$this->_content=file_get_contents($this->getPath());
	}

	public function getPath(){
		return $this->_path;
	}

	public function getContent(){
		return $this->_content;
	}

	public function getData(){
		return json_decode($this->getContent(), true);
	}

	public function getChoices($names=[]){
		$choices=[];
		if(empty($names)){
			$choices=$this->getData()['choices'];
		}else{
			$choices = array_filter($this->getData()['choices'],function($key)use($names){
				return in_array($key,$names);
			},ARRAY_FILTER_USE_KEY );
		}
		return $choices;//$this->getData()['choices'];
	}



}