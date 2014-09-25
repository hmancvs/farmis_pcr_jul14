<?php

class PaymentController extends SecureController   {
	
	function getResourceForACL() {
		return "Payments";
	}
}