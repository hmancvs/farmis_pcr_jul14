<?php

require_once 'doctrine/doctrine.compiled.php';

class Application_Route_Custom extends Zend_Controller_Router_Route {
	
    public static function getInstance(Zend_Config $config){
        $defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
        return new self($config->route, $defs);    
    }
 
    public function __construct($route, $defaults = array()){
        $this->_route = trim($route, $this->_urlDelimiter);
        $this->_defaults = (array)$defaults;
    }
 
    public function match($path, $partial = false) {
        if ($path instanceof Zend_Controller_Request_Http) {
            $path = $path->getPathInfo();
        }
        $path = trim($path, $this->_urlDelimiter);
		//get the vanity controller name i.e the param sent from the url
        $pathBits = explode($this->_urlDelimiter, $path);
 		
        if (count($pathBits) != 1) {
            return false;
        }
		
        # get all controllers in the system
        $path = APPLICATION_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'scripts';
		$appcontrollers = get_dirs($path); 
		// debugMessage($appcontrollers);
		# Only do the redirect if the controller does not exist in the system
		if(!in_array($pathBits[0], $appcontrollers) && !isEmptyString($pathBits[0])){
        	$conn = Doctrine_Manager::connection();
        	$query = "select id from useraccount where username = '".$pathBits[0]."' ";
        	$result = $conn->fetchRow($query);
        		
        	# check database for this company label
        	if($result){
	        	$values = $this->_defaults+$result;
	        	return $values;
        	}
		}
		
        return false;
    }
 
    public function assemble($data = array(), $reset = false, $encode = false){
        return $data['profilelabel'];
    }
}