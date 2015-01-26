<?php

class TestController extends IndexController  {
	
    function smsAction(){
    	// disable rendering of the view and layout so that we can just echo the AJAX output 
	    $this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
	    
	    $session = SessionWrapper::getInstance(); 
	    $formvalues = $this->_getAllParams();
	    // debugMessage($formvalues);
	    $phone = $this->_getParam('phone');
	    $message = $this->_getParam('msg');
	  	$source =  $this->_getParam('source');
	    if(isEmptyString($message)){
	    	$message = "Dear User, This is an automated test message from FARMIS system. confirm route - ".mktime();
	    }
	    if(isEmptyString($phone)){
	    	$phone = SMS_TEST_NUMBER;
	    	if(isKenya()){
	    		$phone = SMS_TEST_NUMBER_KENYA;
	    	}
	    }
	    /* $result = 'SUBMIT_SUCCESS | 74f2b84c-8018-5fbf-c74c-49f7e7d10401';
	    $result_array = explode('|', $result);
	    $result_code = $result_array[0];
	    $conn = Doctrine_Manager::connection();
	    $query = "INSERT INTO outbox (phone, msg, source, result, datecreated, createdby, country) values ('".$phone."', '".$message."', '".$source."', '".$result_code."', '".getCurrentMysqlTimestamp()."', '".$session->getVar('userid')."', '".strtoupper($session->getVar('country'))."') ";
	    $conn->execute($query); */
	    // debugMessage($result);
	    sendSMSMessage($phone, $message, $source);
    }
    
	function sendsmsAction(){
    	// disable rendering of the view and layout so that we can just echo the AJAX output 
	    $this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
	    
	    $session = SessionWrapper::getInstance(); 
	    $formvalues = $this->_getAllParams();
	    // debugMessage($formvalues);
	   	
		$phone = $this->_getParam('phone');
	    if(!isEmptyString($this->_getParam('msisdn'))){
	    	$phone = $this->_getParam('msisdn');
	    }
	    $message = $this->_getParam('msg');
	    if(isEmptyString($message)){
	    	$message = "Dear User, This is an automated test message from FARMIS system. confirm route - ".mktime();
	    }
	    if(isEmptyString($phone)){
	    	$phone = SMS_TEST_NUMBER;
	    	if($this->_getParam('warid') == 1){
	    		$phone = 256701595279;
	    	}
	    	if(isKenya()){
	    		$phone = SMS_TEST_NUMBER_KENYA;
	    	}
	    }
	    // sendSMS($phone, $message, $this->_getParam('source'));
    }
    
    function testmailAction(){
    	$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
	    
    	sendTestMessage('hman test','farmis email testing');
    }
    
    
	function emailAction(){
    	$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
	    
	    sendTestMessage('test farmis email','this is a test message for farmis please ignore - '.APPLICATION_ENV);
    }
    
    function filesAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(TRUE);
    	 
    	$path = APPLICATION_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'scripts';
    	$files = get_dirs($path); debugMessage($files);
    }
	
    function agmisAction(){
    	// disable rendering of the view and layout so that we can just echo the AJAX output
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(TRUE);
    	 
    	$session = SessionWrapper::getInstance();
    	$formvalues = $this->_getAllParams();
    	/* $prices = getLatestPrices('', '', 1);
    	debugMessage($prices); */
    	
    }
}

