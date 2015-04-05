<?php 
use Entity\UserNotifications;
class FlagController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 	}
 
 	function index(){

 		try {
 			$this->checkSession();

 			$flaggedPosts = $this->doctrine->em->getRepository('Entity\FlagLog')->findBy(array(),array('id'=>'desc'));

			$this->load->view('admin/header');
			$this->load->view('admin/flag/list',array('flagged'=>$flaggedPosts));
			$this->load->view('admin/footer');

 		} catch (Exception $e) {
 			echo "<pre>";
 			print_r($e->getMessage());
 			die();
 		}
 	}

 	function warn(){
 		try {
 			$this->checkSession();

 			$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_GET['userId']));

 			if(!is_null($user)){
 				$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_GET['postId']));

 				if(!is_null($post)){
	 				$userNotifications = new UserNotifications();

	 				$userNotifications->setUser($user);
	 				$userNotifications->setNotifyText("You have been warned about your post titled ".$post->getHeadline()." by the Admin");
	 				$userNotifications->setImage(base_url()."assets/images/admin.png");
	 				$userNotifications->setLink(base_url()."single/".$post->getSlug());

	 				$this->doctrine->em->persist($userNotifications);
	 				$this->doctrine->em->flush();

	 				redirect('admin/flagged?success');
 				}else{
 					redirect('admin/flagged?posterror');
 				}
 			}else{
 				redirect('admin/flagged?usererror');
 			}
 		} catch (Exception $e) {
 			echo "<pre>";
 			print_r($e->getMessage());
 			die();
 		}
 	}

 	function checkSession() {
		try {
			session_start();
			if (isset($_SESSION['adminUserId'])) {
				return $_SESSION['adminUserId'];
			} else {
				redirect('admin');
			}
		} catch ( Exception $e ) {
			print_r($e -> getMessage());
		}
	}
 } ?>