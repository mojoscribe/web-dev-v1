<?php
class IndexController extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
		
	}
	
	function index(){
		try {
			session_start();
			if(isset($_SESSION['adminUserName'])) {
				redirect('admin/home');
			}
			$this->load->view('admin/header',array('login'=>true));
			$this->load->view('admin/login');
			$this->load->view('admin/footer');
		} catch(Exception $e){			
			print_r($e);
			redirect('error');
		}
	}
	
	function authenticate(){
		try {
			$user = $this->doctrine->em->getRepository('Entity\Admin')->findOneBy(array('username' => $_POST['userName']));
			if(!is_null($user)) {
				if($user->getPassword() == md5($_POST['password'])) {
					session_start();
				  	$_SESSION['adminUserId'] = $user->getId();
					$_SESSION['adminUserName'] = $user->getUsername();
					redirect('admin/users');
				}else {
					redirect('admin?wrong');
				}
			} else {
				redirect('admin?wrong');
			}
			
		} catch(Exception $e) {
			print_r($e->getMessage());
			redirect('error');
		}
	}
	
	function logout(){
		try {
			session_start();
			session_destroy();
			redirect('admin?logout');
		} catch(Exception $e){
			print_r($e);
			redirect('error');
		}
	}
}