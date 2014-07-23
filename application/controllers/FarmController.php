<?php
class FarmController extends SecureController   {

    public function addAction(){}
    
    public function getActionforACL() {
        $action = strtolower($this->getRequest()->getActionName()); 
		if($action == "add" || $action == "addcrop" || $action == "processadd" || $action == "processaddcrop" || $action == "processother") {
			return ACTION_CREATE; 
		}
    	if($action == "picture" || $action == "processpicture" || $action == "croppicture" || $action == "delete" ||  
    		$action == "addsuccess" || $action == "addcropsuccess" || $action == "events"
    	) {
			return ACTION_VIEW; 
		}
		return parent::getActionforACL(); 
    }
    
    public function getResourceForACL(){
        return "Farmer"; 
    }
    
	public function editAction() {
    	$this->_setParam("action", ACTION_EDIT);
		// debugMessage($this->_getAllParams());
    	// exit();
    	$this->createAction();
    }
    
	public function addsuccessAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("farmer_add_success"));
    	// echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("farmer_invite_success").'</div>';
    }
    
    public function addcropAction(){
		/*$farmcrop = new FarmCrop();
		debugMessage($farmcrop->toArray());
		exit();*/
    }
    
	public function processaddcropAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$session = SessionWrapper::getInstance(); 
		$formvalues = $this->_getAllParams();
		
		// debugMessage($formvalues);
		$post_data = array();
		foreach ($formvalues['cropids'] as $key => $value){
			$post_data[$key]['cropid'] = $value;
			$post_data[$key]['farmerid'] = $formvalues['farmerid'];
			$post_data[$key]['farmid'] = $formvalues['farmid'];
			$post_data[$key]['userid'] = $formvalues['userid'];
		}
		// debugMessage($post_data); 
		$crop_collection = new Doctrine_Collection(Doctrine_Core::getTable("FarmCrop"));
		foreach ($post_data as $thedata){
			$farmcrop = new FarmCrop();
			$farmcrop->processPost($thedata);
			if($farmcrop->isValid()) {
				$crop_collection->add($farmcrop);
			}
		}
		# save the crops
		if($crop_collection->count() > 0){
			$crop_collection->save();
			foreach ($crop_collection as $crop){
				$crop->afterSave();
			}
		}
		
		$session->setVar(SUCCESS_MESSAGE, "Crop(s) Successfully added");
		$this->_helper->redirector->gotoUrl(decode($this->_getParam(URL_SUCCESS))); 
    }
    
	public function addcropsuccessAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$session->setVar(SUCCESS_MESSAGE, "Crop(s) Successfully added");
    	// echo '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'.$this->_translate->translate("farmer_invite_success").'</div>';
    }
    
    
	function deleteAction() {
    	$session = SessionWrapper::getInstance(); 
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$formvalues = $this->_getAllParams();
		$successurl = decode($formvalues['successurl']);
		$classname = $formvalues['entityname'];
		// debugMessage($successurl);
		
    	$obj = new $classname;
    	$obj->populate($formvalues['id']);
    	// debugMessage($obj->toArray());
    	// exit();
    	if($obj->delete()) {
    		$session->setVar(SUCCESS_MESSAGE, $this->_translate->translate("global_delete_success"));
    		$this->_helper->redirector->gotoUrl($successurl);
    	}
    	
    	return false;
    }
}