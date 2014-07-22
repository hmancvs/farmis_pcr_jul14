<?php

class FarmerController extends SecureController   {

	public function getResourceForACL(){
		return "Farmer";
	}

	public function getActionforACL() {
		$action = strtolower($this->getRequest()->getActionName());
		if($action == "add") {
			return ACTION_CREATE;
		}
		return parent::getActionforACL();
	}
}