<?php
class HomeController extends CI_Controller {
	function __construct() {
		parent::__construct ();		
	}

	function index() {
		try {
			$this->checkSession ();
			$this->load->view ( 'admin/header', array (
					'userName' => $_SESSION ['adminUserName'] , 'home' => 1
			) );
			 
			$this->load->view ( 'admin/home'  );
			$this->load->view ( 'admin/footer', array (
					'adminHome' => true 
			) );
		} catch ( Exception $e ) {			
			redirect ( 'error' );
		}
	}
	
	function checkSession() {
		try {
			session_start ();
			if (isset ( $_SESSION ['adminUserId'] )) {
				return $_SESSION ['adminUserId'];
			} else {
				redirect ( 'admin' );
			}
		} catch ( Exception $e ) {
			redirect ( 'error' );
		}
	}
}