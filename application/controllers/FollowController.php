<?php
use Entity\Follow;
use Entity\User;
use Entity\UserNotifications;
class FollowController extends CI_Controller {
	var $followRepository;
	var $userRepository;

	function __construct() {
		parent::__construct();
		$this->followRepository = $this->doctrine->em->getRepository('Entity\Follow');
		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
		$this->load->file('application/classes/GCM.php');
		$this ->load->file('application/classes/Response.php');
	}

	function index() {
		$response = new Response();
		try {
			if(false != checkCsrf()){
				$_POST = file_get_contents("php://input");
				$_POST = json_decode($_POST);
				
				if(false != isUserLoggedIn()){
					$user = isUserLoggedIn();
					$author = $this->userRepository->getUser($_POST);
					
					if(!is_null($user) && !is_null($author)){
						$follow = $this->followRepository->getFollow($user,$author);

						if(false != $follow){
							$this->doctrine->em->remove($follow);

							$notification = $this->doctrine->em->getRepository('Entity\UserNotifications')->findOneBy(array('user'=>$author));

							if(null != $notification){
								$this->doctrine->em->remove($notification);
							}
							// }else{
							// 	$response->setSuccess(false);
							// 	$response->setData('');
							// 	$response->setError(array(
							// 		'msg'=>'notification error'
							// 		));

							// 	$response->respond();
							// 	die();
							// }
							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array(
								'code'=>'9502',
								'msg'=>'Follow removed'
								));
							$response->setError('');
						}else{
							$newFollow = new Follow();
							$newFollow->setAuthor($author);
							$newFollow->setUser($user);
							$this->doctrine->em->persist($newFollow);

							$notification = new UserNotifications();
							$notification->setNotifyText($user->getUserName()." has started following you.");
							$notification->setLink(base_url().$user->getUserName());
							$notification->setUser($author);
							$notification->setImage($user->getProfilePicturePath());
							$notification->setActionType('user');
							$notification->setActionId($user->getId());

							if(NULL !== $author->getGcmId() && "" != $author->getGcmId() && 0 != $author->getGcmId()){
								$gcm = new GCM();

								$message = array(
									'msg'=>$user->getUserName()." has started following you.",
									'action'=>array(
										'type'=>'user',
										'id'=>$user->getId()
									)
								);

								header("Content-Type: application/json");
								$message = ($message);

								$ids = array();

								$ids[] = $author->getGcmId();

								$result = $gcm->send_notification($ids,$message);
								
							}

							$this->doctrine->em->persist($notification);
							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array(
								'code'=>'9501',
								'msg'=>'Author followed'
								));
							$response->setError('');
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'msg'=>'Author or user error'
							));
					}
		   		}else{
		   			$response->setSuccess(false);
		   			$response->setData('');
		   			$response->setError(array(
		   				'code'=>'2006',
		   				'msg'=>'User not logged In'));
		   		}
		   	}else{
		   		$response->setSuccess(false);
		   		$response->setData('');
		   		$response->setError(array(
		   			'msg'=>'Cross domain requests are not allowed'
		   		));
		   	}
		} catch(Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}

		$response->respond();
		die();
	}

	function isFollow(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if(false != isUserLoggedIn()){
					$user = isUserLoggedIn();

					$_POST = file_get_contents("php://input");

					$author = $this->userRepository->getUser($_POST);

					if(!is_null($author) && !is_null($user)){
						$follow = $this->followRepository->getFollow($user,$author);

						if(false != $follow){
							$response->setSuccess(true);
							$response->setData(array(
								'code'=>'9503',
								'msg'=>'Following'
								));
							$response->setError('');
						}else{
							$response->setSuccess(true);
							$response->setData(array(
								'code'=>'9504',
								'msg'=>'Not following'
								));
							$response->setError('');
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'9001',
							'msg'=>'Author not found'
							));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'2006',
						'msg'=>'User not logged In'
						));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>$e->getMessage()
					));
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

	function checkSession() {
		try {
			if (!isset($_SESSION['id'])) {
				session_start();
			}
			if (isset($_SESSION['id'])) {
				return $_SESSION['id'];
			}
		} catch ( PDOException $e ) {
			print_r($e -> getMessage());
			redirect('error');
		}
	}
}
