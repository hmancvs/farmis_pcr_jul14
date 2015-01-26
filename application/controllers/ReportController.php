<?php
class ReportController extends SecureController   {
	
	/**
	 * Get the name of the resource being accessed
	 *
	 * @return String
	 */
	function getActionforACL() {
		return ACTION_VIEW;
	}
	/**
	 * @see SecureController::getResourceForACL()
	 * 
	 * Return the Application Settings since we need to make the url more friendly 
	 *
	 * @return String
	 */
	function getResourceForACL() {
		$resource = strtolower($this->getRequest()->getActionName()); 
		if ($resource == "dashboard" || $resource == "reportsearch") {
			return "Report Dashboard";
		}
		if ($resource == "certificate" || $resource == "certificatesearch") {
			return "Farmer Membership Certificate";
		}
		if ($resource == "dnacertificate" || $resource == "dnacertificatesearch") {
			return "DNA Membership Certificate";
		}
		if ($resource == "primarybaseline") {
			return "Farmers Baseline Summary Report";
		}
		if ($resource == "baselinedetail") {
			return "Farmers Baseline Detail Report";
		}
		if ($resource == "allfarmers" || $resource == "allfarmersdata") {
			return "Farmers Bio Data Report";
		}
		if ($resource == "dna" || $resource == "dnasearch") {
			return "DNA Profiling Aggregated Report";
		}
		if ($resource == "location" || $resource == "locationsearch") {
			return "District and Location Profiling Report";
		}
		if ($resource == "crop" || $resource == "cropsearch") {
			return "Crop Profiling Aggregated Report";
		}
		if ($resource == "pia" || $resource == "piasearch") {
			return "PIA Profiling Performance Report";
		}
		if ($resource == "statement") {
			return "Profit and Loss Statement";
		}
		if ($resource == "prices" || $resource == "pricessearch") {
			return "Market Prices";
		}
		parent::getResourceForACL();
	}
	
	function dashboardAction() {
		 
	}
	function primarybaselineAction(){
		
	}	
	function baselinedetailAction(){
		
	}
	
	function allfarmersAction(){
		$listcount = new LookupType();
    	$listcount->setName("LIST_ITEM_COUNT_OPTIONS");
    	$values = $listcount->getOptionValues(); 
    	asort($values, SORT_NUMERIC); 
    	$session = SessionWrapper::getInstance();
    	
    	$dropdown = new Zend_Form_Element_Select('itemcountperpage',
							array(
								'multiOptions' => $values, 
								'view' => new Zend_View(),
								'decorators' => array('ViewHelper'),
							     'class' => array('span1')
							)
						);
		if (isEmptyString($this->_getParam('itemcountperpage'))) {
			$dropdown->setValue('ALL');
			if(!isEmptyString($session->getVar('itemcountperpage'))){
				$dropdown->setValue($session->getVar('itemcountperpage'));
			}
		} else {
			$session->setVar('itemcountperpage', $this->_getParam('itemcountperpage'));
			$dropdown->setValue($session->getVar('itemcountperpage'));
		}  
	    $this->view->listcountdropdown = '<span class="pull-right">'.$this->_translate->translate("global_list_itemcount_dropdown").$dropdown->render().'</span>';
	}
	
	function allfarmersdataAction(){
		$this->_helper->layout->disableLayout();
	}
	
	function allfarmerssearchAction(){
		$this->_helper->redirector->gotoSimple("allfarmers", "report", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function certificateAction(){
		
	}
	
	function certificatesearchAction(){
		$this->_helper->redirector->gotoSimple("certificate", "report", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function dnacertificateAction(){
	
	}
	
	function dnacertificatesearchAction(){
		$this->_helper->redirector->gotoSimple("dnacertificate", "report",
				$this->getRequest()->getModuleName(),
				array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function dnaAction(){
		
	}
	function dnasearchAction(){
		$this->_helper->redirector->gotoSimple("dna", "report", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function locationAction(){
		
	}
	function locationsearchAction(){
		$this->_helper->redirector->gotoSimple("location", "report", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function piaAction(){
		
	}
	function piasearchAction(){
		$this->_helper->redirector->gotoSimple("pia", "report", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function cropAction(){
		
	}
	function cropsearchAction(){
		$this->_helper->redirector->gotoSimple("crop", "report", 
    											$this->getRequest()->getModuleName(),
    											array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	function pricesAction(){
		
	}
	
	public function statementAction() {
	}
	
	public function statementsearchAction() {
		$this->_helper->redirector->gotoSimple('statement', 'finance',
				$this->getRequest()->getModuleName(),
				array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
	}
	
	/**
	 * Redirect list searches to maintain the urls as per zend format
	 */
	public function reportsearchAction() {
		// debugMessage($this->getRequest()->getQuery());
		// debugMessage($this->_getAllParams());
		$action = $this->_getParam('page');
		// exit();
		if(!isEmptyString($action)){
			$this->_helper->redirector->gotoSimple($action, $this->getRequest()->getControllerName(),
					$this->getRequest()->getModuleName(),
					array_remove_empty(array_merge_maintain_keys($this->_getAllParams(), $this->getRequest()->getQuery())));
		}
	}
}
