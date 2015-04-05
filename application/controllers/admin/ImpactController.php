<?php 
use Entity\Impact;
class ImpactController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 	}
 
 	function index(){
 		$this->checkSession ();
 		try {
 			$this->load->view ( 'admin/header', array (
					'userName' => $_SESSION ['adminUserName'] , 'home' => 1
			) );
			$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();
			$this->load->view('admin/impact/list',array('impacts' => $impacts));

			$this->load->view ( 'admin/footer', array (
					'adminHome' => false 
			));
 		} catch (Exception $e) {
 			
 		}
 		
 	}

 	function add(){

 		$this->checkSession();	
 		try {
 			$this->load->view ( 'admin/header', array (
					'userName' => $_SESSION ['adminUserName'] , 'home' => 1
			) );
			 
			$this->load->view('admin/impact/add');

			$this->load->view ( 'admin/footer', array (
					'adminHome' => false 
			) );
 		} catch (Exception $e) {
 			
 		}
 	}

 	function save(){
 		$this->checkSession();
 		try {
 			$impact = new Impact();
 			$impact->setArea($_POST['name']);
 			$this->doctrine->em->persist($impact);
 			$this->doctrine->em->flush();
 			redirect('admin/impacts?add=success');
 		} catch (Exception $e) { 		
 			redirect('admin/impacts?add=fail&msg='.$e->getMessage());
 		}
 	}

 	function delete(){ 		
 		echo $_GET['id'];
 		try {
 			$impact = $this->doctrine->em->getRepository('Entity\Impact')->findOneBy(array('id' => $_GET['id']));
 			if(!is_null($impact)) {
 				$this->doctrine->em->remove($impact); 				
 				$this->doctrine->em->flush();
 				redirect('admin/impacts');
 			} else {
 				redirect('admin/impacts');
 			}
 		} catch (Exception $e) {
 			redirect('admin/impacts');
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