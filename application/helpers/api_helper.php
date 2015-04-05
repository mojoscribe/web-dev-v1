<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('generateAuthToken')){
	function generateAuthToken() {		
		return time();
	}
}

if(!function_exists('checkAuthToken')){
	function checkAuthToken(){
		$CI = get_instance();
		$em = $CI->doctrine->em;
		$headers = apache_request_headers();
		$user = $em->getRepository('Entity\User')->findOneBy(array('authToken' => $headers['auth-token']));

		if($user) {
			return $user;
		} 
		return false;		
	}
}


if(!function_exists('checkApiKey')){
	function checkApiKey(){
		$headers = apache_request_headers();
		if(isset($headers['api-key'])){
			if(API_KEY == $headers['api-key']){
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
}

if(!function_exists('cleanReqVars')) {

	function cleanReqVars(){
		$CI = get_instance();
		$arr = $CI->input->post(NULL,true);		
		return $arr;
	}
}