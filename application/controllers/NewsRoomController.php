<?php
use Entity\User;
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
	}

	function index(){
		try {
			$id = $this->checkSession();
			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();
				$userData['userName'] = $user->getUserName();
				$follower = $this->userRepository->getUser($id);
				$author = $user;
				$follow = $this->followRepository->getFollow($follower,$author);
				$followStatus = FALSE;
				if($follow != FALSE) {
					// if($follow->getIsFollow() == 1)
					//{ $followStatus = TRUE; }
				}

				$userData['profilePicture'] = $user->getProfilePicturePath();
				$userData['joinDate'] = $user->getCreatedOn();
				$userData['userId'] = $user->getId();
				$userData['about'] = $user->getAbout();
				$subscribers = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('author'=>$user));

				if($user->getCity() != "" && $user->getCountry() != ""){
					$userData['location'] = $user->getCity().",".$user->getCountry();
				}else{
					$userData['location'] = "";
				}

				// if($user->getCountry() != ""){
				// 	$userData['country'] = $user->getCountry();
				// }else{
				// 	$userData['country'] = "";
				// }


				if(!is_null($subscribers)){
					$userData['subscribers'] = count($subscribers);
				}else{
					$userData['subscribers'] = 0;
				}

				$data = array();
				$data['userName'] = $user->getUserName();
				$data['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$data));
				$this->load->view('navigation');
				$this->load->view('user/newsRoom',array('followStatus' => $followStatus,'data'=>$userData,'author'=>$author));
				$this->load->view('footer',array('scripts'=>array('controllers/newsRoomController.js','controllers/sliderController.js')));

			}else{
				redirect('/?sessionexpired');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function loadNewsRoom(){
		try {
			
			session_start();
			if(isset($_SESSION['userName']) && $_SESSION['userName'] == $this->uri->segment(1)){
				redirect('newsRoom');
			}else {
				$userName = $this->uri->segment(1);

				$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('userName'=>$userName));

				$userData = array();
				if(null != $user){
					$userData['userName'] = $user->getUserName();				
					$userData['profilePicture'] = $user->getProfilePicturePath();					
					$userData['joinDate'] = $user->getCreatedOn();
					$userData['userId'] = $user->getId();
					$userData['about'] = $user->getAbout();

					if($user->getGender() == "male"){
						$userData['gender'] = "His";
					}elseif ($user->getGender() == "female") {
						$userData['gender'] = "Her";
					}

					if($user->getCity() != "" && $user->getCountry() != ""){
						$userData['location'] = $user->getCity().",".$user->getCountry();
					}else{
						$userData['location'] = "";
					}

					// if(){
					// 	$userData['country'] = $user->getCountry();
					// }else{
					// 	$userData['country'] = "";
					// }


					$subscribers = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('author'=>$user));

					if(!is_null($subscribers)){
						$userData['subscribers'] = count($subscribers);
					}else{
						$userData['subscribers'] = 0;
					}

					$author = $user;

					if(isset($_SESSION['id'])){

						$loggedInUser = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

						$follower = $this->userRepository->getUser($_SESSION['id']);
						
						
			            $follow = $this->followRepository->getFollow($follower,$author);
			          
			            $followStatus = FALSE;
			            if($follow != FALSE){
					  		$followStatus = TRUE;
						}

						$data = array();
						$data['userName'] = $loggedInUser->getUserName();
						$data['picture'] = $loggedInUser->getProfilePicturePath();

						$this->load->view('header',array('data'=>$data));
						$this->load->view('navigation');
						$this->load->view('user/othersNewsRoom',array('data'=>$userData,'followStatus' => $followStatus,'author'=>$author));
						$this->load->view('footer',array('scripts'=>array('controllers/newsRoomController.js')));
					}else{
						$this->load->view('header');
						$this->load->view('navigation');
						$this->load->view('user/othersNewsRoom',array('data'=>$userData,'author'=>$author));
						$this->load->view('footer',array('scripts'=>array('controllers/newsRoomController.js')));
					}
				}else{
					// if(isset($_SESSION['id'])){
					// 	$data = array();
					// 	$data['userName'] = $loggedInUser->getUserName();
					// 	$data['picture'] = $loggedInUser->getProfilePicturePath();

					// 	$this->load->view('header',array('data'=>$data));

					// 	$this->load->view('footer');
					// }else{
					// 	$this->load->view('header');
					// 	$this->load->view('footer');
					// }
					redirect('notFound');
					// show_404('error/notFound');
				}
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function getRecentPosts(){
		$response = new Response();

		if(false != checkCsrf()){


			$userId = $_GET['userId'];

			$user = $this->userRepository->getUser($userId);
			$email = $user->getEmail();

			if($_SERVER['REQUEST_METHOD'] == "GET"){
				$howMany = 0;
				if(isset($_GET['howMany'])){
					$howMany = $_GET['howMany'];
				}else{
					$howMany = 10;
				}
				$posts = $this->postRepository->getRecentPostsByUser($email,$howMany);

				if($posts){
					$data = array();
					foreach ($posts as $post) {
						$temp['id'] = $post->getId();
						$temp['title'] = $post->getHeadline();
						$temp['date'] = $post->getUpdatedOn()->format('d-M-Y');
						$temp['hashtags'] = array();

						if(null != $post->getHashtags()){
							foreach ($post->getHashtags() as $hashtag) {
								$hash['name'] = $hashtag->getHashtag();
								$temp['hashtags'][] = $hash['name'];
							}
						}else{
							$temp['hashtags'] = null;
						}

						$temp['file'] = array();
						$tmpFiles = array();
						foreach ($post->getFiles() as $file) {
							$tmpFiles['thumb'] = base_url($file->getThumb());
						}

						$temp['file'] = $tmpFiles;
						$temp['showImage'] = true;

						$temp['postType'] = $post->getPostType();
						$temp['slug'] = $post->getSlug();

						$data[] = $temp;
					}

					$response->setSuccess(true);
					$response->setData($data);
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setError(array('msg'=>'Posts do not exist'));
				}
			}else{
				$response->setSuccess(false);
				$response->setError(array('msg'=>'Method Error'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		// } else {
		// 	$response->setSuccess(false);
		// 	$response->setError(array('msg'=>'Session Expired'));
		// }

		$response->respond();
		die();
	}

	function getRatedPosts(){
		$response = new Response();

		if(false != checkCsrf()){
			try {
				
				$userId = $_GET['userId'];
				
				$user = $this->userRepository->getUser($userId);

				$posts = $this->postRepository->getRatedPostsForUser($user);

				$data = array();

				if($posts){
					foreach ($posts as $post) {
						$temp['id'] = $post->getPost()->getId();
						$temp['title'] = $post->getPost()->getHeadline();
						$temp['date'] = $post->getPost()->getUpdatedOn()->format('d-M-Y');
						$temp['hashtags'] = array();

						if($post->getPost()->getIsAnonymous() == true){
							$temp['author'] = "Anonymous";
						}else{
							$temp['author'] = $post->getPost()->getAuthor()->getUserName();
						}

						$temp['slug'] = $post->getPost()->getSlug();

						if(null != $post->getPost()->getHashtags()){
							foreach ($post->getPost()->getHashtags() as $hashtag) {
								$hash['name'] = $hashtag->getHashtag();
								$temp['hashtags'][] = $hash['name'];
							}
						}else{
							$temp['hashtags'] = null;
						}

						$temp['file'] = array();

						$tmpFiles = array();
						foreach ($post->getPost()->getFiles() as $file) {
							$tmpFiles['thumb'] = base_url($file->getThumb());
						}

						$temp['file'] = $tmpFiles;

						$temp['postType'] = $post->getPost()->getPostType();

						$data[] = $temp;
					}

					$response->setSuccess(true);
					$response->setData($data);
					$response->setError('');
				}else{
					$response->setSuccess(true);
					$response->setData(array('msg'=>'No Data for the user'));
					$response->setError('');
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

	function getSubscriptions(){
		$response = new Response();

		if(false != checkCsrf()){
		
		// if(false != isUserLoggedIn()){

			$userId = $_GET['userId'];
			
			$user = $this->userRepository->getUser($userId);

			$subscriptions = $this->postRepository->getSubscriptionsForUser($user);

			$data = array();

			if(null != $subscriptions){
				
				foreach ($subscriptions as $subscription) {
					$temp['id'] = $subscription->getId();
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

					$data[] = $temp;
				}

				$response->setSuccess(true);
				$response->setData($data);
				$response->setError('');
			}else{
				$response->setSuccess(true);
				$response->setData(array('msg'=>'No Data for the user'));
				$response->setError('');
			}
		// }else{

		// 	$response->setSuccess(false);
		// 	$response->setData(array('msg'=>'jdhbc'));
		// 	$response->setError(array('msg'=>'User not logged in'));
		// }
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


	function getRecentFiles(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "POST"){

					$_POST = file_get_contents("php://input");

					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_POST));

					if(false != $user){
						$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('author'=>$user,'postStatus'=>'PUBLISHED','isAnonymous'=>0),array('postRanking'=>'desc'),5);

						if(!is_null($posts)){
							$files = array();

							foreach ($posts as $post) {
								foreach ($post->getFiles() as $file) {
									$temp['file'] = base_url($file->getNewsroom());

									if($post->getPostType() == "Image"){
										$temp['showImage'] = true;
										$temp['showVideo'] = false;
									}elseif($post->getPostType() == "Video"){
										$temp['showVideo'] = true;
										$temp['showImage'] = false;
									}

									$files[] = $temp;
								}
							}

							$response->setSuccess(true);
							$response->setData($files);
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'',
								'msg'=>'No Posts available'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'',
							'msg'=>'User not found'
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
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'',
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