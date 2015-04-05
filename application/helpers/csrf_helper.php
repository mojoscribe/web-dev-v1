<?php
function checkCsrf(){
	// return true;
	session_start();
	$headers = apache_request_headers();

	// echo "<pre>";
	// print_r($_COOKIE['XSRF_TOKEN']);
	// echo "string";
	// print_r($headers['XSRF_TOKEN']);
	// die();

	if(isset($_SESSION['fromView']) && $_SESSION['fromView'] == true){
		return true;
	}else{
		// return false;
		return false;	
	}
}

?>