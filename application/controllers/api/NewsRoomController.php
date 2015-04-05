<?php
use Entity\Follow;
use Entity\UserNotifications;
class NewsRoomController extends CI_Controller {
	var $postRepository;
	var $followRepository;
	var $userRepository;
	function __construct(){
		parent::__construct();
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->followRepository = $this->doctrine->em->getRepository('Entity\Follow');
		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
		$this->load->file('application/classes/Response.php');
		$this->load->file('application/classes/GCM.php');
		$this->load->helper('api');
	}

	function index(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(checkApiKey()){
					if($_SERVER['REQUEST_METHOD'] == "POST"){
						if(isset($_POST['userId'])){
							$userId = $_POST['userId'];
							$user = $this->userRepository->findOneBy(array('id' => $userId));
							if(!is_null($user)) {
								$userData['id'] = $user->getId();
								$userData['userName'] = $user->getUserName();
								$userData['fullName'] = $user->getFirstName() . " " . $user->getLastName();
								$userData['profilePicture'] = $user->getProfilePicturePath();
								$userData['joinDate'] = $user->getCreatedOn()->format('d-m-Y');						
								if(is_null($user->getAbout())){
									$userData['about'] = "";
								}else{
									$userData['about'] = $user->getAbout();
								}

								$subscribers = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('author'=>$user));

								if(!is_null($subscribers)){
									$userData['subscribers'] = count($subscribers);
								}else{
									$userData['subscribers'] = 0;
								}
								

								if(is_null($user->getGender())){
									$userData['gender'] = "";
								}else{
									$userData['gender'] = $user->getGender();
								}
								
								$userData['location'] = $user->getCity().", ".$user->getCountry();

								$userData['isFollow'] = null;

								if(isset($headers['auth-token']) && $headers['auth-token'] != ""){
									$loggedInUser = checkAuthToken();
									$isFollow = $this->followRepository->getFollow($loggedInUser,$user);

									if(false != $isFollow){
										$userData['isFollow'] = true;
									}else{
										$userData['isFollow'] = false;
									}
								}else{

								}

								//Recent Posts
								$recentPosts = $this->postRepository->getRecentPostsByUser($user->getEmail(),6);						
								$posts = array();

								if($recentPosts != ""){
									foreach($recentPosts as $post) {
										$temp = array();
										$temp['id'] = $post->getId();
										$temp['title'] = $post->getHeadline();
										$temp['date'] = $post->getUpdatedOn()->format('d-M-Y');
										$temp['hashtags'] = array();

										if(null != $post->getHashtags()){
											foreach ($post->getHashtags() as $hashtag) {
												$hash = array();
												$hash['id'] = $hashtag->getId();
												$hash['name'] = $hashtag->getHashtag();
												$temp['hashtags'][] = $hash;
											}
										}else{
											$temp['hashtags'] = null;
										}

										$temp['type'] = $post->getPostType();

										$temp['file'] = array();
										if(null != $post->getFiles()){
											foreach ($post->getFiles() as $file) {
												if(null != $file){
													$temp['file'] = base_url($file->getDeviceImage());
												}else{
													$temp['file'] = "";
												}

												break;
											}
										}else{
											$temp['file'] = "";

										}
										
										// $temp['postType'] = $post->getPostType();
										$posts[] = $temp;
									}
								}


								//Rated Posts
								$rated = $this->postRepository->getRatedPostsForUser($user);
								$ratedPosts = array();
								if($rated != ""){
									foreach ($rated as $post) {
										$temp = array();
										$temp['id'] = $post->getPost()->getId();
										$temp['title'] = $post->getPost()->getHeadline();
										$temp['date'] = $post->getPost()->getUpdatedOn()->format('d-M-Y');
										$temp['hashtags'] = array();

										if(null != $post->getPost()->getHashtags()){
											foreach ($post->getPost()->getHashtags() as $hashtag) {
												$hash = array();
												$hash['id'] = $hashtag->getId();
												$hash['hashtag'] = $hashtag->getHashtag();
												$temp['hashtags'][] = $hash;
											}
										}else{
											$temp['hashtags'] = null;
										}

										$temp['file'] = array();
										if(null != $post->getPost()->getFiles()){
											foreach ($post->getPost()->getFiles() as $file) {
												if(null != $file){
													$temp['file'] = base_url($file->getDeviceImage());

												}else{
													$temp['file'] = "";
												}
												break;
											}
										}else{
											$temp['file'] = "";
										}
										$temp['postType'] = $post->getPost()->getPostType();

										$ratedPosts[] = $temp;
									}
								}

								//Subscriptions
								$subscriptions = $this->postRepository->getSubscriptionsForUser($user);						
								$userSubs = array();
								if($subscriptions != ""){
									foreach ($subscriptions as $subscription) {
										$temp = array();
										$temp['id'] = $subscription->getUser()->getId();
										$temp['followedUserId'] = $subscription->getAuthor()->getId();
										
										if($subscription->getAuthor()->getProfilePicturePath() == null){
											$temp['followedUserPicture'] = "";	
										}else{
											$temp['followedUserPicture'] = $subscription->getAuthor()->getProfilePicturePath();
										}

										$temp['followedUserUserName'] = $subscription->getAuthor()->getUserName();
										$temp['followedUserJoinDate'] = $subscription->getAuthor()->getCreatedOn();
										$temp['followedUserJoinDate'] = $temp['followedUserJoinDate']->format("d-M-Y");

										$subscribers = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('author'=>$subscription->getAuthor()));

										if(!is_null($subscribers)){
											$temp['subscribers'] = count($subscribers);
										}else{
											$temp['subscribers'] = 0;
										}

										$temp['reporterLevel'] = 0; 

										$userSubs[] = $temp;
									}
								}

								$response->setSuccess(true);
								$response->setData(array(
										'user' => $userData,
										'recentPosts' => $posts,
										'ratedPosts' => $ratedPosts,
										'subscriptions' => $userSubs,
									));
								$response->setError("");

							} else {
								$response->setSuccess(false);
								$response->setData("");
								$response->setError(array(
									'code' => 2002,
									'msg' => "User with ID does not exist."
								));
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>3001,
								'msg'=>'User ID not set'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>1100,
							'msg'=>'Method Error'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>1099,
						'msg'=>'Invalid API Key'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>1098,
					'msg'=>'API Key not set'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg' => $e->getMessage()));
		}

		$response->respond();
		die();
	}

	function followUser(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(checkApiKey()){
				if(isset($headers['auth-token'])){
					if(checkAuthToken()){
						$user = checkAuthToken();
						if(isset($_POST['userId'])){
							$author = $this->userRepository->getUser($_POST['userId']);

							if(false != $author){
								$follow = $this->followRepository->getFollow($user,$author);

								if(false != $follow){
									$this->doctrine->em->remove($follow);
									$this->doctrine->em->flush();

									$response->setSuccess(true);
									$response->setData(array(
										'code'=>'9502',
										'msg'=>'Follow removed'
										));
									$response->setError('');
								}else{
									$newFollow = new Follow();
									$newFollow->setUser($user);
									$newFollow->setAuthor($author);

									$notification = new UserNotifications();
									$notification->setNotifyText($user->getUserName()." has started following you.");
									$notification->setLink(base_url().$user->getUserName());
									$notification->setUser($author);
									$notification->setImage($user->getProfilePicturePath());
									$notification->setActionType('user');
									$notification->setActionId($user->getId());

									if(null != $author->getGcmId() || "" != $author->getGcmId()){
										$gcm = new GCM();

										$message = array(
											'msg'=>$user->getUserName()." has started following you.",
											'action'=>array(
												'type'=>'user',
												'id'=>$user->getId()
											)
										);

										header("Content-Type: application/json");
										// $message = json_encode($message);

										$result = $gcm->send_notification(array($author->getGcmId()),$message);
										
									}

									$this->doctrine->em->persist($notification);
									$this->doctrine->em->persist($newFollow);
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
									'code'=>'9001',
									'msg'=>'Author not found'
									));
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'9002',
								'msg'=>'Author Id not set'
								));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'2001',
							'msg'=>'User with auth token not found'
							));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'2005',
						'msg'=>'Auth token not set'
						));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'1099',
					'msg'=>'Invalid API key'
					));
			}
		} catch (Exception $e) {
			$$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'code'=>'',
				'msg'=>$e->getMessage()
				));
		}
		$response->respond();
		die();
	}
}
?>