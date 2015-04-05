<?php
use Entity\UserNotifications;
class NotificationsController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 	}
 
 	function index(){
 		try {
			if(false != isUserLoggedIn()){
		 		$user = isUserLoggedIn();

		 		$profile['id'] = $user->getId();
		 		$profile['userName'] = $user->getUserName();
		 		$profile['picture'] = $user->getProfilePicturePath();

		 		$notifications = $this->doctrine->em->getRepository('Entity\UserNotifications')->findBy(array('user'=>$user),array('id'=>'desc'),50);

				$data = array();

				if(!empty($notifications)){
					foreach ($notifications as $notification) {
						$temp['id'] = $notification->getId();
						$temp['link'] = $notification->getLink();
						$temp['text'] = $notification->getNotifyText();
						$temp['image'] = $notification->getImage();
						$temp['date'] = $notification->getUpdatedOn()->format('d-M-Y');

						$data[] = $temp;
					}
				}

		 		$this->load->view('header',array('data'=>$profile));
		 		// $this->load->view('user/header');
		 		$this->load->view('user/notifications',array('notifications'=>$data));
		 		$this->load->view('footer');
			}else{
				redirect('/');
			}
 		} catch (Exception $e) {
 			// echo "<pre>";
 			// print_r($e->getMessage());
 			// die();
 			redirect('technicalProblem');
 		}
 	}

 	function getNotifications(){
 		$response = new Response();
 		if(false != checkCsrf()){
	 		try {
	 			if($_SERVER['REQUEST_METHOD'] == "POST"){
	 				if(false != isUserLoggedIn()){
	 					$user = isUserLoggedIn();

	 					$notifications = $this->doctrine->em->getRepository('Entity\UserNotifications')->findBy(array('user'=>$user),array('id'=>'desc'),10);

	 					$data = array();
	 					$count = 0;

	 					if(!empty($notifications)){
		 					foreach ($notifications as $notification) {
		 						$temp['id'] = $notification->getId();
		 						$temp['link'] = $notification->getLink();
		 						$temp['text'] = $notification->getNotifyText();
		 						$temp['image'] = $notification->getImage();
		 						$temp['read'] = $notification->getIsRead()."sdkjn";
		 						if($notification->getIsRead() == false || $notification->getIsRead() == ""){
		 							$count++;
		 						}

		 						$data[] = $temp;
		 					}
		 					
	 						$response->setSuccess(true);
		 					$response->setData(array('data'=>$data,'count'=>$count));
		 					$response->setError('');

	 					}else{
	 						$response->setSuccess(false);
	 						$response->setData('');
	 						$response->setError(array(
	 							'msg'=>'No notifications for the user'
							));
	 					}
	 				}else{
	 					$response->setSuccess(false);
	 					$response->setData('');
	 					$response->setError(array(
	 						'code'=>'',
	 						'msg'=>'User not logged in'
	 					));
	 				}
	 			}else{
	 				$response->setSuccess(false);
	 				$response->setData('');
	 				$response->setError(array(
	 					'msg'=>'Method Error'
	 				));
	 			}
	 		} catch (Exception $e) {
	 			$response->setSuccess(false);
	 			$response->setData('');
	 			$response->setError(array('msg'=>$e->getMessage()));
	 		}
	 	}else{
	 		$response->setSuccess(false);
	 		$response->setData('');
	 		$response->setError(array(
	 			'msg'=>'Cross domain requests are not allowed'
	 		));
	 	}

 		$response->respond();
 		die();
 	}

 	function makeRead(){
 		$response = new Response();

 		if(false != checkCsrf()){
	 		try {
	 			if(isUserLoggedIn()){
					$user = isUserLoggedIn();

					$notifications = $this->doctrine->em->getRepository('Entity\UserNotifications')->findBy(array('user'=>$user));

					if(($notifications)){
						foreach ($notifications as $n) {
							$n->setIsRead(true);
							$this->doctrine->em->persist($n);
						}
						
						$this->doctrine->em->flush();

						$response->setSuccess(true);
						$response->setData(array('msg'=>'Read'));
						$response->setError('');
					}
	 			}else{
	 				$response->setSuccess(false);
	 				$response->setData('');
	 				$response->setError(array(
	 					'msg'=>'User not logged in'
	 				));
	 			}
	 		} catch (Exception $e) {
	 			$response->setSuccess(false);
	 			$response->setData('');
	 			$response->setError(array('msg'=>$e->getMessage()));
	 		}
	 	}else{
	 		$response->setSuccess(false);
	 		$response->setData('');
	 		$response->setError(array(
	 			'msg'=>'Cross domain requests are not allowed'
	 		));
	 	}

 		$response->respond();
 		die();
 	}
 } ?>