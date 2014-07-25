<?php

class RoleController extends SecureController   {
	
	/**
	 * Override unknown actions to enable ACL checking 
	 * 
	 * @see SecureController::getActionforACL()
	 *
	 * @return String
	 */
	public function getActionforACL() {
        $action = strtolower($this->getRequest()->getActionName()); 
		if($action == "processroles") {
			return ACTION_CREATE; 
		}
		if($action == "processroles" && !isEmptyString($this->_getParam('id'))) {
			return ACTION_EDIT;
		}
		return parent::getActionforACL(); 
	}
    
	public function processrolesAction(){
		$session = SessionWrapper::getInstance(); 
     	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		$post_array = $this->_getAllParams(); // debugMessage($this->_getAllParams()); exit; 
		$id = $post_array['id']; 
		$post_array['id'] = decode($id);
		$perms = $post_array['permissions'];
		unset($post_array['permissions']);
		
		if(isEmptyString($id)){
			// add new role first
			$newrole = new AclGroup();
			$post_array['createdby'] = $session->getVar('userid');
			$newrole->processPost($post_array); // debugMessage($newrole->toArray()); debugMessage('error is '.$newrole->getErrorStackAsString());
			$newrole->save();
			$post_array['id'] = $newrole->getID();
		} else {
			// update role first
			$newrole = new AclGroup();
			$newrole->populate(decode($id));
			$newrole->processPost($post_array); debugMessage($newrole->toArray()); debugMessage('error is '.$newrole->getErrorStackAsString()); // exit();
			$newrole->save();
		}
		// exit;
		$role = new AclGroup();
		$role->populate($post_array['id']);
		$permissions = $role->getPermissions();
		$permissions_array = $permissions->toArray();
		$post_array['permissions'] = $perms;
		
		if(!isArrayKeyAnEmptyString('permissions', $post_array)){
			$data = array();
			foreach($post_array['permissions'] as $key => $value) {
				$data[$key] = $value;
				$post_array['permissions'][$key]['groupid'] = $post_array['id'];
				if(isArrayKeyAnEmptyString('flag', $value)){
					$post_array['permissions'][$key]['flag'] = 0;
				} else {
					$post_array['permissions'][$key]['flag'] = trim(intval($value['flag']));
				}
				if(isArrayKeyAnEmptyString('create', $value)){
					$post_array['permissions'][$key]['create'] = 0;
				} else {
					$post_array['permissions'][$key]['create'] = trim(intval($value['create']));
				}
				if(isArrayKeyAnEmptyString('edit', $value)){
					$post_array['permissions'][$key]['edit'] = 0;
				} else {
					$post_array['permissions'][$key]['edit'] = trim(intval($value['edit']));
				}
				if(isArrayKeyAnEmptyString('view', $value)){
					$post_array['permissions'][$key]['view'] = 0;
				} else {
					$post_array['permissions'][$key]['view'] = trim(intval($value['view']));
				}
				if(isArrayKeyAnEmptyString('list', $value)){
					$post_array['permissions'][$key]['list'] = 0;
				} else {
					$post_array['permissions'][$key]['list'] = trim(intval($value['list']));
				}
				if(isArrayKeyAnEmptyString('delete', $value)){
					$post_array['permissions'][$key]['delete'] = 0;
				} else {
					$post_array['permissions'][$key]['delete'] = trim(intval($value['delete']));
				}
				if(isArrayKeyAnEmptyString('approve', $value)){
					$post_array['permissions'][$key]['approve'] = 0;
				} else {
					$post_array['permissions'][$key]['approve'] = trim(intval($value['approve']));
				}
				if(isArrayKeyAnEmptyString('export', $value)){
					$post_array['permissions'][$key]['export'] = 0;
				} else {
					$post_array['permissions'][$key]['export'] = trim(intval($value['export']));
				}
				if(isArrayKeyAnEmptyString('id', $value)){
					$post_array['permissions'][$key]['id'] = NULL;
				}
				$post_array['permissions'][$key]['createdby'] = $session->getVar('userid');
				$post_array['permissions'][$key]['datecreated'] = getCurrentMysqlTimestamp();
				if(!isArrayKeyAnEmptyString('id', $value)){
					$post_array['permissions'][$key]['lastupdatedby'] = $session->getVar('userid');
					$post_array['permissions'][$key]['lastupdatedate'] = getCurrentMysqlTimestamp();
				} else {
					$post_array['createdby'] = $session->getVar('userid');
				}
			} // end loop through permissions to unset empty groupids 
		}
		// debugMessage($post_array); exit();
		
		$perm_collection = new Doctrine_Collection(Doctrine_Core::getTable("AclPermission"));
		foreach($post_array['permissions'] as $key => $value) {
			$perm = new AclPermission();
			if(!isArrayKeyAnEmptyString('id', $value)){
				$perm->populate($value['id']);
			}
			
			$perm->processPost($value);
			if($perm->isValid()) {
				$perm_collection->add($perm);
			} else {
				debugMessage('Error: '.$perm->getErrorStackAsString());
				exit();
			}
		}
		
		try {
			$perm_collection->save();
			$this->_helper->redirector->gotoUrl($this->view->baseUrl("role/view/id/".encode($role->getID())));
		} catch (Exception $e) {
			// debugMessage($perm_collection->toArray()); 
			// debugMessage('error in save. '.$e->getMessage());
			$session->setVar(ERROR_MESSAGE, $e->getMessage());
			$this->_helper->redirector->gotoUrl($this->view->baseUrl("role/index/id/".encode($role->getID())));
		}
	}
}

