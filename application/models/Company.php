<?php

/**
 * Model for company 
 *
 */
class Company extends BaseEntity  {
	
	public function setTableDefinition() {
		#add the table definitions from the parent table
		parent::setTableDefinition();
		
		// set the table
		$this->setTableName('company');
		$this->hasColumn('type', 'integer', null, array('notblank' => true, 'default' => '1')); // 1 Partner, 2 to be continue
		$this->hasColumn('name', 'string', 255, array('notblank' => true));
		$this->hasColumn('description', 'string', 500);
		$this->hasColumn('contactperson', 'string', 255);
		$this->hasColumn('phone', 'string', 15);
		$this->hasColumn('email', 'string', 255);
		$this->hasColumn('country', 'string', 2, array('default' => 'UG'));
		$this->hasColumn('locationid', 'integer', null);
		$this->hasColumn('status', 'integer', null, array('default' => '1'));
		$this->hasColumn('farmistype', 'integer', null, array('default' => '1'));
		// 1=>All Farmers, 2=>One region, 3=>Multiple regions, 4=>One District, 5=>Multiple districts, 6=>One dna, 7=> Multiple dnas
		$this->hasColumn('regionid', 'integer', null);
		$this->hasColumn('regionids', 'string', 50);
		$this->hasColumn('districtid', 'integer', null);
		$this->hasColumn('districtids', 'string', 50);
		$this->hasColumn('dnaid', 'integer', null);
		$this->hasColumn('dnaids', 'string', 50);
	}
	
	/**
	 * Contructor method for custom functionality - add the fields to be marked as dates
	 */
	public function construct() {
		parent::construct();
		// set the custom error messages
       	$this->addCustomErrorMessages(array(
       									"name.notblank" => $this->translate->_("company_name_error")
       	       						));
	}
	public function setUp() {
		parent::setUp();
		
		$this->hasOne('Location as location',
				array(
						'local' => 'locationid',
						'foreign' => 'id'
				)
		);
		$this->hasOne('Location as region',
				array(
						'local' => 'regionid',
						'foreign' => 'id'
				)
		);
		$this->hasOne('Location as district',
				array(
						'local' => 'districtid',
						'foreign' => 'id'
				)
		);
		$this->hasOne('FarmGroup as dna',
				array(
						'local' => 'dnaid',
						'foreign' => 'id'
				)
		);
	}
	/*	
	 * Custom model validation
	 */
	function validate() {
		// execute the column validation 
		parent::validate();
		
		// connection		
		$conn = Doctrine_Manager::connection();
		
		// query for check if location exists
		$unique_query = "SELECT id FROM company WHERE name = '".$this->getName()."' AND id <> '".$this->getID()."'";
		$result = $conn->fetchOne($unique_query);
		// debugMessage($unique_query);
		// debugMessage("result is ".$result);
		if(!isEmptyString($result)){ 
			$this->getErrorStack()->add("unique.name", "The name ".$this->getName()." already exists. Please specify another.");
		}
	}
	/*
	 * Preprocess model data
	 */
	function processPost($formvalues) {
		// check if the parentid is specified
		if (isArrayKeyAnEmptyString('type', $formvalues)) {
			unset($formvalues['type']);
		}
		if (isArrayKeyAnEmptyString('farmistype', $formvalues)) {
			unset($formvalues['farmistype']);
		}
		if (isArrayKeyAnEmptyString('status', $formvalues)) {
			unset($formvalues['status']);
		}
		
		if(!isArrayKeyAnEmptyString('theregionids', $formvalues)) {
			$ids = $formvalues['theregionids'];
			$typelist = '';
			if(count($ids) > 0){
				$typelist = createHTMLCommaListFromArray($ids, ",");
			}
			$formvalues['regionids'] = $typelist;
			# remove the usergroups_groupid array, it will be ignored, but to be on the safe side
			unset($formvalues['theregionids']);
		} else {
			if(!isArrayKeyAnEmptyString('regionids_old', $formvalues)){
				$formvalues['regionids'] = NULL;
			} else {
				unset($formvalues['regionids']);
			}
		}
		
		if(!isArrayKeyAnEmptyString('thedistrictids', $formvalues)) {
			$ids = $formvalues['thedistrictids'];
			$typelist = '';
			if(count($ids) > 0){
				$typelist = createHTMLCommaListFromArray($ids, ",");
			}
			$formvalues['districtids'] = $typelist;
			# remove the usergroups_groupid array, it will be ignored, but to be on the safe side
			unset($formvalues['thedistrictids']);
		} else {
			if(!isArrayKeyAnEmptyString('districtids_old', $formvalues)){
				$formvalues['districtids'] = NULL;
			} else {
				unset($formvalues['districtids']);
			}
		}
		
		if(!isArrayKeyAnEmptyString('thednaids', $formvalues)) {
			$ids = $formvalues['thednaids'];
			$typelist = '';
			if(count($ids) > 0){
				$typelist = createHTMLCommaListFromArray($ids, ",");
			}
			$formvalues['dnaids'] = $typelist;
			# remove the usergroups_groupid array, it will be ignored, but to be on the safe side
			unset($formvalues['thednaids']);
		} else {
			if(!isArrayKeyAnEmptyString('dnaids_old', $formvalues)){
				$formvalues['dnaids'] = NULL;
			} else {
				unset($formvalues['dnaids']);
			}
		}
		// debugMessage($formvalues); exit;
		parent::processPost($formvalues);
	}
	# determine the allocation type
	function getAllocationTypeLabel(){
		$types = getPartnerAllocationTypes();
		if(isEmptyString($this->getFarmisType())){
			return '';
		}
		if(isArrayKeyAnEmptyString($this->getFarmisType(), $types)){
			return '';
		}
		return $types[$this->getFarmisType()];
	}
	# determine the allocation details from allocation type
	function getAllocationDetails(){
		if(isEmptyString($this->getAllocationTypeLabel())){
			return '';
		}
		$text = '';
		$select_array = array();
		switch ($this->getFarmisType()){
			case 0:
				$text = '';
			case 1:
				$text = '';
				break;
			case 2:
				$text = $this->getRegion()->getName();
				break;
			case 3:
				if(isEmptyString($this->getRegionIDs())){
					$text = '';
				} else {
					$select_array = explode(",", trim($this->getRegionIDs())); // debugMessage($select_array);
					$locationname = array();
					$locations = getRegions('UG');
					foreach ($select_array as $key => $value){
						$locationname[$value] = $locations[$value];
					}
					$text = createHTMLCommaListFromArray($locationname,  ', ');
				}
				break;
			case 4:
				$text = $this->getDistrict()->getName();
				break;
			case 5:
				if(isEmptyString($this->getDistrictIDs())){
					$text = '';
				} else { 
					$select_array = explode(",", trim($this->getDistrictIDs())); // debugMessage($select_array);
					$locationname = array();
					$locations = getDistricts('UG');
					foreach ($select_array as $key => $value){
						$locationname[$value] = $locations[$value];
					}
					$text = createHTMLCommaListFromArray($locationname,  ', ');
				}
				break;
			case 6:
				$text = $this->getDNA()->getName();
				break;
			case 7:
				if(isEmptyString($this->getDNAIDs())){
					$text = '';
				} else {
					$select_array = explode(",", trim($this->getDNAIDs())); // debugMessage($select_array);
					$groupname = array();
					$groups = getAllDNAs('UG');
					foreach ($select_array as $key => $value){
						$groupname[$value] = $groups[$value];
					}
					$text = createHTMLCommaListFromArray($groupname,  ', ');
				}
				break;
			default:
				break;
		}
		return $text;
	}
	# Return an array containing the IDs 
	function getTheRegionIDs(){
		return isEmptyString($this->getRegionIDs()) ? array() : explode(",", trim($this->getRegionIDs())); 
	}
	function getTheDistrictIDs(){
		return isEmptyString($this->getDistrictIDs()) ? array() : explode(",", trim($this->getDistrictIDs()));
	}
	function getTheDNAIDs(){
		return isEmptyString($this->getDNAIDs()) ? array() : explode(",", trim($this->getDNAIDs()));
	}
}

?>