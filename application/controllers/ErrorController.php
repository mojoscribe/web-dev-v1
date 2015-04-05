<?php

class ErrorController extends CI_Controller {
	function index() {
		try {

			session_start();
			if(isset($_SESSION['id'])){
				$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));
				$data = array();
				$data['userName'] = $user->getUserName();
				$data['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$data));
				$this->load->view('error/notFound');
				$this->load->view('footer');
			
			}else{

				$this->load->view('header');
				$this->load->view('error/notFound');
				$this->load->view('footer');
			
			}
		} catch (Exception $e) {
			redirect('error');
		}
	}

	function technicalProblem(){
		session_start();
		if(isset($_SESSION['id'])){
			
			$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));
			
			$data = array();
			$data['userName'] = $user->getUserName();
			$data['picture'] = $user->getProfilePicturePath();

			$this->load->view('header',array('data'=>$data));
			$this->load->view('error/technical');
			$this->load->view('footer');

		}else{
			
			$this->load->view('header');
			$this->load->view('error/technical');
			$this->load->view('footer');

		}
	}
}
