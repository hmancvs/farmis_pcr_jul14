<?php

class WeatherController extends IndexController  {
	
	function init(){
		parent::init();
		
	}
    function indexAction(){
	    $session = SessionWrapper::getInstance(); 
	    $formvalues = $this->_getAllParams();
	    
    }
    
	function forecastAction() {
    	$this->_helper->viewRenderer->setNoRender(TRUE);
	    $formvalues = $this->_getAllParams();
    	debugMessage($formvalues);
    	// exit();
    	
    }
	
    function wrapperAction() {
    	// $this->_helper->viewRenderer->setNoRender(TRUE);
	    // $formvalues = $this->_getAllParams();
    	// debugMessage($formvalues);
    	// exit();
    	
    }
    
    function weathersummaryAction(){
    	$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
    	$config = Zend_Registry::get("config");
    	include_once (APPLICATION_PATH.'/includes/forecast.io.php');
    	
    	$location = new Location();
    	if(isEmptyString($this->_getParam('locationid'))){
    		$this->_setParam('locationid', 35);
    	}
    	$location->populate($this->_getParam('locationid')); // debugMessage($location->toArray());
    	
    	// $api_key = $config->api->weather_forecast_apikey;
    	$api_key = '507a232d6fe4c5dd408b9cda4b2b4010';
		$latitude = $location->getGpsLat(); // '0.350110'
		$longitude = $location->getGpsLng(); // '32.571910';
		
		$forecast = new ForecastIO($api_key); 
		$condition = $forecast->getCurrentConditions($latitude, $longitude, mktime(), 'minutely,hourly,daily'); 
		if(!$condition){
			$data = array(
				"time" => '',
				"summary" => '',
				"icon" => '',
				"temperature" => '',
				"apparentTemperature" => '',
				"humidity" => '',
				"wind" => '',
				"windSpeed" => '',
				"cloudCover" => '',
				"pressure" => ''
			);
			$data['location'] = $location->getName();
			$data['locationid'] = $location->getID();
		} else {
			$data = array(
				"time" => $condition->getTime('Y-m-d H:i:s'),
				"summary" => $condition->getSummary(),
				"icon" => $condition->getIcon(),
				"temperature" => number_format($condition->getTemperature()).'&deg;C',
				"apparentTemperature" => number_format($condition->getApparentTemperature()).'&deg;C',
				"humidity" => number_format($condition->getHumidity()*100).'%',
				"wind" => $condition->getWindBearing(),
				"windSpeed" => $condition->getWindSpeed().' mph(S)',
				"cloudCover" => $condition->getCloudCover(),
				"pressure" => number_format($condition->getPressure()).' mb'
			);
			$data['location'] = $location->getName();
			$data['locationid'] = $location->getID();
		}
		// debugMessage($data);
		echo json_encode($data);
    }
    
	function weathertodayAction(){
    	$this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(TRUE);
    	$config = Zend_Registry::get("config");
    	include_once (APPLICATION_PATH.'/includes/forecast.io.php');
    	
    	$location = new Location();
    	if(isEmptyString($this->_getParam('locationid'))){
    		$this->_setParam('locationid', 35);
    	}
    	$location->populate($this->_getParam('locationid'));
    	
    	// $api_key = $config->api->weather_forecast_apikey; 
    	$api_key = '507a232d6fe4c5dd408b9cda4b2b4010';
		$latitude = $location->getGpsLat(); // '0.350110'
		$longitude = $location->getGpsLng(); // '32.571910';
		$forecast = new ForecastIO($api_key);
		$condition = $forecast->getForecastWeek($latitude, $longitude); // exit();
		$today = $condition{0};
		if(!$today){
			$data = array(
				"time" => '',
				"summary" => '',
				"icon" => '',
				"temperature" => '',
				"apparentTemperature" => '',
				"humidity" => '',
				"wind" => '',
				"windSpeed" => '',
				"cloudCover" => '',
				"pressure" => ''
			);
			$data['location'] = $location->getName();
			$data['locationid'] = $location->getID();
		} else {
			$data = array(
				"summary" => $today->getSummary(),
				"icon" => $today->getIcon(),
				"temperaturemin" => number_format($today->getMinTemperature()).'&deg;C',
				"temperaturemintime" => $today->getMinTemperatureTime('ga'),
				"temperaturemax" => number_format($today->getMaxTemperature()).'&deg;C',
				"temperaturemaxtime" => $today->getMaxTemperatureTime('g A'),
				"sunrise" => $today->getSunrise('g:i A'),
				"sunset" => $today->getSunset('g:i A'),
				"humidity" => $today->getHumidity(),
				"wind" => $today->getWindBearing(),
				"windSpeed" => $today->getWindSpeed(),
				"cloudCover" => $today->getCloudCover(),
				"pressure" => $today->getPressure()
			);
			$now = mktime();
			$risestr = "will rise";
			$setstr = "will set";
			if($now > $today->getSunrise()){
				$risestr = "rose";
			}
			if($now > $today->getSunset()){
				$setstr = "set";
			}
			$string = $data['summary'].", with a highest temperature of ".$data['temperaturemax']." at around ".
			$data['temperaturemaxtime'].", and lowest temperature of ".$data['temperaturemin']." at around ".
			$data['temperaturemintime'].". The sun ".$risestr." at ".$data['sunrise']." and ".$setstr." at ".$data['sunset'].".";
			$data['description'] = '<h3 style="font-size:16px; padding-left:20px;">'.$location->getName().'</h3><span class="span12" style="line-height:28px;">'.$string.'<span>';
			$data['location'] = $location->getName();
			$data['locationid'] = $location->getID();
			// debugMessage($string);
		}
		// debugMessage($data);
		echo json_encode($data);
    }
}

