<?php

class ApiController extends IndexController  {

    function indexAction() {
    	$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
	    echo 'NULL_PARAMETER_LIST';
    }
    
    function subscriberAction() {
    	$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
	    $conn = Doctrine_Manager::connection(); 
    	$session = SessionWrapper::getInstance();
    	$formvalues = $this->_getAllParams();
    	
    	$country = 'UG'; $location = 'district';
    	if(isKenya() || strtolower($this->_getParam('country' == 'ke'))){
    		$country = 'KE';
    		$session->setVar('country', 'ke');
    		$location = 'county';
    	}
    	
    	$feed = '';
    	$issearch = false;
    	$iscolumn = false;
    	$where_query = " "; $having_query = " ";
    	$group_query = " GROUP BY u.id ";
    	$limit_query = "";
    	$now = DEFAULT_DATETIME;
    	
    	# search by column attribute
    	if($this->_getParam('filter') == 'attr'){
    		$iscolumn = true;
    		# fetch subscribers by type
    		if(!isEmptyString($this->_getParam('type'))){
    			$where_query .= " AND u.type = '".$this->_getParam('type')."' ";
    		}
    		# fetch subscribers by login status
    		if(!isEmptyString($this->_getParam('loginstatus'))){
    			$where_query .= " AND u.isactive = '".$this->_getParam('loginstatus')."' ";
    		}
    		# fetch subscribers by payment status
    		if(!isEmptyString($this->_getParam('paymentstatus'))){
    			# check for paid up farmers
    			if($this->_getParam('paymentstatus') == 1){
    				$where_query .= " AND u.paymentstatus = '".$this->_getParam('paymentstatus')."' AND TO_DAYS(NOW()) BETWEEN TO_DAYS(u.startdate) AND TO_DAYS(u.enddate) ";
    			}
    			# check for farmers currrently still on trial
    			if($this->_getParam('paymentstatus') == 2){
    				$where_query .= " AND u.paymentstatus <> 1 AND TO_DAYS(CURDATE()) BETWEEN TO_DAYS(u.datecreated) AND TO_DAYS(DATE_ADD(u.datecreated, INTERVAL 7 DAY)) ";
    			}	
    			# check for expired farmers
    			if($this->_getParam('paymentstatus') == 0){
    				$where_query .= " AND ((TO_DAYS(NOW()) NOT BETWEEN TO_DAYS(u.startdate) AND TO_DAYS(u.enddate)) OR (TO_DAYS(CURDATE()) NOT BETWEEN TO_DAYS(u.datecreated) AND TO_DAYS(DATE_ADD(u.datecreated, INTERVAL 7 DAY))) )";
    			}
    		}
    			 
    		# fetch subscribers by phone
    		if(!isEmptyString($this->_getParam('phone'))){
    			if(!is_numeric($this->_getParam('phone')) || strlen($this->_getParam('phone')) != 12){
    				echo 'PHONE_INVALID'; 
    				exit();
    			}
    			$where_query .= " AND u.phone = '".$this->_getParam('phone')."' ";
    		}
    		# fetch by email
    		if(!isEmptyString($this->_getParam('email'))){
    			$where_query .= " AND u.email = '".$this->_getParam('email')."' ";
    		}
    		# fetch by username
    		if(!isEmptyString($this->_getParam('username'))){
    			$where_query .= " AND u.username = '".$this->_getParam('username')."' ";
    		}
    		# fetch by service
    		if(!isEmptyString($this->_getParam('serviceid'))){
    			$where_query .= " AND FIND_IN_SET('".$this->_getParam('serviceid')."', u.services) > 0 ";
    		}
    		# fetch by district
    		if(!isEmptyString($this->_getParam('districtid'))){
    			$where_query .= " AND u.locationid = '".$this->_getParam('districtid')."' ";
    		}
    		# fetch by dna
    		if(!isEmptyString($this->_getParam('dnaid'))){
    			$where_query .= " AND u.farmgroupid = '".$this->_getParam('dnaid')."' ";
    		}
    		# fetch by gender
    		if(!isEmptyString($this->_getParam('gender'))){
    			$where_query .= " AND u.gender = '".$this->_getParam('gender')."' ";
    		}
    		# fetch by cropid
    		if(!isEmptyString($this->_getParam('cropid'))){
    			$where_query .= " AND fc.cropid = '".$this->_getParam('cropid')."' ";
    		}
    		# fetch by profiler
    		if(!isEmptyString($this->_getParam('profiledby'))){
    			$where_query .= " AND u.createdby = '".$this->_getParam('profiledby')."' ";
    		}
    		# fetch by reg period
    		if(!isEmptyString($this->_getParam('regstart')) && !isEmptyString($this->_getParam('regend'))){
    			$where_query .= " AND TO_DAYS(u.datecreated) BETWEEN TO_DAYS('".$this->_getParam('regstart')."') AND TO_DAYS('".$this->_getParam('regend')."') ";
    		}
    		# fetch by payment in period
    		if(!isEmptyString($this->_getParam('paystart')) && !isEmptyString($this->_getParam('payend'))){
    			$where_query .= " AND TO_DAYS(p.trxdate) BETWEEN TO_DAYS('".$this->_getParam('paystart')."') AND TO_DAYS('".$this->_getParam('payend')."') ";
    		}
    		# fetch by mobile access
    		if(!isEmptyString($this->_getParam('hasphone'))){
    			if($this->_getParam('hasphone') == 1){
    				$where_query .= " AND u.phone <> '' ";
    			}
    			if($this->_getParam('hasphone') == 0){
    				$where_query .= " AND (u.phone = '' OR u.phone is null) ";
    			}
    		}
    		# fetch by crops
    		if(!isEmptyString($this->_getParam('hascrops'))){
    			if($this->_getParam('hascrops') == 1){
    				$having_query .= " HAVING COUNT(fc.cropid) > 0 ";
    			}
    		}
    		# fetch by dna status
    		if(!isEmptyString($this->_getParam('hasdna'))){
    			if($this->_getParam('hasdna') == 1){
    				$where_query .= " AND u.farmgroupid <> '' ";
    			}
    			if($this->_getParam('hasdna') == 0){
    				$where_query .= " AND (u.farmgroupid = '' OR u.farmgroupidis null) ";
    			}
    		}
    		# fetch by locationid status
    		if(!isEmptyString($this->_getParam('haslocation'))){
    			if($this->_getParam('haslocation') == 1){
    				$where_query .= " AND u.locationid <> '' ";
    			}
    			if($this->_getParam('haslocation') == 0){
    				$where_query .= " AND (u.locationid = '' OR u.locationid null) ";
    			}
    		}
    	}

    	# search general
    	if($this->_getParam('filter') == 'search'){
    		$issearch = true;
    		if(isEmptyString($this->_getParam('searchterm'))){
    			echo "NO_SEARCHTERM_SPECIFIED";
    			exit();
    		}
    		$searchterm = $this->_getParam('searchterm');
    		$where_query .= " AND (u.phone LIKE '%".$searchterm."%' OR u.firstname LIKE '%".$searchterm."%'
    		OR u.lastname LIKE '%".$searchterm."%' OR u.email LIKE '%".$searchterm."%' OR u.username LIKE '%".$searchterm."%'
    		)";
    	}
	    
    	if(!isEmptyString($this->_getParam('limit'))){
    		$limit_query .= " LIMIT ".$this->_getParam('limit');
    	}
    	
    	if(isEmptyString($where_query)){
    		echo "NULL_PARAMETER_LIST";
    	} else {
	   	 	$query = "SELECT 
	   	 	u.id,
    		u.refno,
    		concat(u.firstname,' ',u.lastname,' ',u.othernames) as `name`,
    		fg.orgname as dna,
    		u.farmgroupid as dnaid,    		
    		l.name as district,
    		u.locationid as districtid,
    		u.phone,
    		u.email,
    		u.dateofbirth,
    		u.isactive, 
    		u.paymentstatus, 
    		u.datecreated as regdate, 
    		u.startdate, 
    		u.enddate,
    		p.trxdate as paymentdate,
    		u.services as serviceids,
    		u.languages as languageid,
    		u.lat as lat,
    		u.lng as lng,
    		group_concat(fc.cropid SEPARATOR ',') as cropidlist
	   	 	FROM useraccount u 
	   	 	INNER JOIN location l ON (u.locationid = l.id AND l.locationtype = 2) 
	   	 	LEFT JOIN farmgroup fg ON (u.farmgroupid = fg.id)  
	   	 	LEFT JOIN payment p ON (u.paymentid = p.id)  
	   	 	LEFT JOIN farmcrop fc ON (fc.userid = u.id) 
	   	 	WHERE UPPER(u.country) = UPPER('".$country."') ".$where_query." ".$group_query.$having_query." ORDER BY u.datecreated DESC ".$limit_query;
	    	// debugMessage($query); // exit();
	    	
	    	$result = $conn->fetchAll($query);
	    	$count = count($result);
	    	/* debugMessage($result); 
	    	exit(); */
	    	
	    	if($count == 0){
	    		echo "RESULT_NULL";
	    		exit();
	    	} else {
	    		foreach ($result as $line){
	    			$user = new UserAccount();
	    			$user->populate($line['id']);
	    			$croplist = '';
	    			$cropids = '';
	    			
	    			$ids = array(); $names = array();
	    			$crops = $user->getTheCrops();
	    			if($crops){
	    				//debugMessage($groups->toArray());
	    				foreach($crops as $crop) {
	    					if($crop->getCrop()->getCategoryID() != 27){
	    						$ids[] = $crop->getCropID();
	    						$names[] = $crop->getCrop()->getName();
	    					}
	    				}
	    			}
	    			if(count($ids) > 0){
	    				$cropids = implode(',', $ids);
	    				$croplist = implode(',', $names);
	    			}
	    			exit();
	    			$start = $line['startdate']; $end = $line['enddate'];
	    			if(isEmptyString($line['startdate'])){
	    				$start = date('Y-m-d', strtotime($line['regdate']));
	    				$expiredays = '30';
	    				$end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start)). " +".$expiredays." days "));
	    			}
	    			
	    			
			    	$feed .= '<subscriber>';
		    		$feed .= '<id>'.$line['id'].'</id>';
		    		$feed .= '<refno>'.$line['refno'].'</refno>';
		    		$feed .= '<name>'.$line['name'].'</name>';
		    		$feed .= '<phone>'.$line['phone'].'</phone>';
			    	$feed .= '<email>'.$line['email'].'</email>';
			    	$feed .= '<dna>'.$line['dna'].'</dna>';
			    	$feed .= '<dnaid>'.$line['dnaid'].'</dnaid>';
			    	$feed .= '<'.$location.'>'.$line['district'].'</'.$location.'>';
			    	$feed .= '<'.$location.'id>'.$line['districtid'].'</'.$location.'id>';
			    	$feed .= '<gpslat>'.$line['lat'].'</gpslat>';
			    	$feed .= '<gpslng>'.$line['lat'].'</gpslng>';
			    	$feed .= '<dateofbirth>'.$line['dateofbirth'].'</dateofbirth>';
			    	$feed .= '<regdate>'.date('Y-m-d', strtotime($line['regdate'])).'</regdate>';
			    	$feed .= '<paymentdate>'.$line['paymentdate'].'</paymentdate>';
			    	$feed .= '<subscriptionstart>'.$start.'</subscriptionstart>';
			    	$feed .= '<subscriptionend>'.$end.'</subscriptionend>';
			    	$feed .= '<paymentstatus>'.$line['paymentstatus'].'</paymentstatus>';
			    	$feed .= '<loginstatus>'.$line['isactive'].'</loginstatus>';
			    	$feed .= '<serviceids>'.$line['serviceids'].'</serviceids>';
			    	$feed .= '<languageid>'.$line['languageid'].'</languageid>';
			    	$feed .= '<cropids>'.$cropids.'</cropids>';
			    	$feed .= '<crops>'.$croplist.'</crops>';
				    $feed .= '</subscriber>';
		    	}
	    	}
    	}
    	
    	# output the xml returned
    	if(isEmptyString($feed)){
    		echo "EXCEPTION_ERROR";
    		exit();
    	} else {
    		// header("Content-type: text/xml");
    		echo '<?xml version="1.0" encoding="UTF-8"?><subscribers>'.$feed.'</subscribers>';
    	}
    }
}

