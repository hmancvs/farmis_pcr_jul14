<?php

class CompanyController extends SecureController  {
	
	function getResourceForACL() {
		// return "Message";
		return "Partner"; 
	}
	
}