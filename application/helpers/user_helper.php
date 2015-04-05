<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('isLoggedIn')){
	function isUserLoggedIn() {		
		if(session_id() == '') {
			session_start();
		}
		/*print_r($_SESSION);
		die();*/
		if(isset($_SESSION['id'])){
			$CI = get_instance();
			$user = $CI->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

			if(1 == $user->getIsVerified() && 0 == $user->getIsBannedStatus()){
				return $user; 
			}else{
				return false;
			}
		}
		return false;
	}
}

function getUserName(){
	if(isUserLoggedIn()) {
		return $_SESSION['userName'];
	}
}

function getUserId() {
	if(isUserLoggedIn()) {
		return $_SESSION['id'];
	}	
}

function isAdmin(){
	$id = getUserId();
	$CI = get_instance();
	$user = $CI->doctrine->em->getRepository('Entity\User')->findOneBy(array('id' => $id));
	if($user) {
		if($user->getRole()->getName() == 'Admin') {
			return true;
		}
		return false;		
	}
	return false;
}