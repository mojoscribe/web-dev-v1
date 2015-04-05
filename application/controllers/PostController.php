<?php
use Entity\Post;
use Entity\Category;
use Entity\Hashtags;
use Entity\Impact;
use Entity\File;
use Entity\PostDetails;
use Entity\FlagLog;
use Entity\ToProcess;
use Entity\UserNotifications;

class PostController extends CI_Controller {

	var $userRepository;
	var $postRepository;
	var $impactRepository;
	var $categoryRepository;
	var $hashtagRepository;
	var $post;
	var $ratingLogRepository;
	var $postDetailsRepository;
	function __construct() {
		parent::__construct();
		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->impactRepository = $this->doctrine->em->getRepository('Entity\Impact');
		$this->categoryRepository = $this->doctrine->em->getRepository('Entity\Category');
		$this->hashtagRepository = $this->doctrine->em->getRepository('Entity\Hashtags');
		$this->ratingLogRepository = $this->doctrine->em->getRepository('Entity\RatingLog');
		$this->postDetailsRepository = $this->doctrine->em->getRepository('Entity\PostDetails');
		$this->load->helper('file_helper');
		$this->load->file('application/classes/Response.php');
		$this->load->file('application/classes/ImageHandler.php');
		$this->load->file('application/classes/VideoHandler.php');
		$this->load->file('application/classes/GCM.php');
		// error_reporting(0);
	}


	function index(){

		try{
			
			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();

				$email = $user->getEmail();
				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();

				$user = $this->userRepository->checkUser($email);
				$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();
				$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();

				if($user){

					$this->load->view('header',array('data'=>$profile));
					// $this->load->view('user/header');
					$this->load->view('user/sidebar');
					$this->load->view('user/upload',array('categories' => $categories, 'impacts'=>$impacts));
					$this->load->view('user/footer');
					$this->load->view('footer',array(
						'scripts' => array('validations/postValidations.js','controllers/postcontroller.js', 'bxslider/jquery.bxslider.min.js','slider.js'),
						'styles' => array('js/bxslider/jquery.bxslider.css')
						));
				}else{
					redirect('/?usernotexist');
				}
			}else{
				redirect('/?sessionexpired');
			}
		}catch(Exception $e)
		{
         // print_r($e->getMessage());
		 redirect('technicalProblem');
		}
	}

	function postSubmitted(){
		$response = new Response();
		//$imgHelper = new ImageHandler();
		
		$_POST = json_decode($_POST['post']);

		$_POST = get_object_vars($_POST);
		
		$this->load->library('image_lib');
		if(false != checkCsrf()){

			if(false != isUserLoggedIn()){

				$user = isUserLoggedIn();

				if($_SERVER['REQUEST_METHOD'] == "POST"){

					try {

						extract($_POST);

						$post = "";
						if(isset($_POST['id']) && null != $_POST['id']){
							$post = $this->postRepository->getPost($_POST['id']);
							if($_POST['postType'] == "Image"){
								$post->setPostStatus("PUBLISHED");
							}else{

								if(!$post->getFiles()->isEmpty()){

									$userName = "";

									$file = $post->getFiles();
									$file = $file[0];

									$filePa = explode('/', $file->getFilePath());

									if($type == "user"){
										$videoHandler = new VideoHandler($filePa[1]);
										// TODO: Watermark user name 
										$videoHandler->waterMarkImage($user->getUserName());
										$userName = $user->getUserName();
									}
									
									$post->setPostStatus("PROCESSING");
									$this->doctrine->em->persist($post);

									$toProcess = new ToProcess();
									$toProcess->setFile($file);
									$toProcess->setUserName($userName);
									$toProcess->setVidPath($filePa[1]);

									$this->doctrine->em->persist($toProcess);
									$this->doctrine->em->flush();

									//Add notification for user - "Your video is being processed"
									$notify = new UserNotifications();
									$notify->setNotifyText("Your video post is being processed and will be published soon");
									$notify->setLink("#");
									$notify->setUser($user);
									$notify->setImage(base_url($file->getThumb()));
									$notify->setActionType('POST');
									$notify->setActionId($post->getId());

									$this->doctrine->em->persist($notify);	
									$this->doctrine->em->flush();
								}
							}
						}else{
							$post = new Post();
							$post->setPostStatus("PUBLISHED");
						}
						
						$slug = $this->postRepository->createSlug(strip_tags($title));
						$post->setSlug($slug);

						if($type == "user"){
							$post->setIsAnonymous(false);
						}elseif ($type == "anonymous") {
							$post->setIsAnonymous(true);
						}

						if($_FILES != null){
							if($_POST['postType'] == "Image"){
								$post->setPostType($postType);

								$file_ary = reArrayFiles($_FILES['files']);

								foreach ($file_ary as $image) {

								    $config ['upload_path'] = './uploads/';
								   	$config ['allowed_types'] = 'jpeg|png|jpg|gif';
								    $config ['max_size'] = '30000'; //30MB  /*What is the limit?*/
								    $config['min_width'] = '250';
								    $config['min_height'] = '200';


								    $this->load->library ( 'upload', $config );

									$_FILES['images']['name']= $image['name'];
						            $_FILES['images']['type']= $image['type'];
						            $_FILES['images']['tmp_name']= $image['tmp_name'];
						            $_FILES['images']['error']= $image['error'];
						            $_FILES['images']['size']= $image['size'];


									if(!$this->upload->do_upload('images')) {
									
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array('msg'=>$this->upload->display_errors()));

										$response->respond();
										die();
										
									} else {
										$data = $this->upload->data();
										chmod("uploads/".$data['file_name'],0777);
										$filePath = "uploads/".$data['file_name'];

										//Resize image and watermark
										$imageHandler = new ImageHandler($data['file_name']);									
										$resized = $imageHandler->createSizes();

										if($type == "user"){
											// TODO: Watermark user name  
											$imageHandler->waterMark($user->getUserName(), $data['file_name']);
										}
										
										$this->load->library ( 'upload', $config );
										$file = new File();
										$file->setFilePath(base_url('uploads/'.$data['file_name']));
										$file->setType("IMAGE");

										$file->setSmall($resized['small']);
										$file->setThumb($resized['thumb']);
										$file->setLong($resized['long']);
										$file->setBig($resized['big']);
										$file->setNewsroom($resized['newsroom']);
										$file->setDeviceImage($resized['device']);
										

										$file->setPost($post);
										$this->doctrine->em->persist($file);
										$post->addFiles($file);
										$this->doctrine->em->persist($post);
									}
								}

							}else{  // Post type is video
								$post->setPostType($postType);

								$reArrayFiles = draftArrayFiles($_FILES['files']);
								
								foreach ($reArrayFiles as $video) {

									$_FILES['video']['name']= $video['name'];
						            $_FILES['video']['type']= $video['type'];
						            $_FILES['video']['tmp_name']= $video['tmp_name'];
						            $_FILES['video']['error']= $video['error'];
						            $_FILES['video']['size']= $video['size'];

									$config ['upload_path'] = './uploads/';
									$config ['allowed_types'] = 'avi|mp4|3gp|mpg|mov';
									$config ['max_size'] = '30000'; //30MB  /*What is the limit?*/

									$this->load->library ( 'upload', $config );

									if (!$this->upload->do_upload('video')) {
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array('msg'=>$this->upload->display_errors()));

										$response->respond();
										die();
									} else {

										$data = $this->upload->data();

										chmod('uploads/'.$data['file_name'], 0777);
										$oldPath = $data['file_name'];
										$newPath = str_replace('(', "", $oldPath);
										$newPath = str_replace(')', "", $newPath);

										// rename('/var/www/html/uploads/'.$data['file_name'], '/var/www/html/uploads/'.$newPath);


										$videoHandler = new VideoHandler($newPath);
										//$watermarkedVideo = watermarkVideo('uploads/'.$data['file_name'],$user->getUserName(),$post->getSlug());
										$resized = $videoHandler->generateThumbnail();

										/*$imageHandler = new ImageHandler($thumb);
										$resized = $imageHandler->createSizes();*/
										$userName = "";
										if($type == "user"){
											// TODO: Watermark user name 
											$videoHandler->waterMarkImage($user->getUserName());
											$userName = $user->getUserName();
										}

										// Watermark video and save in different format
										// TODO: call this function via cron
										// $vidFormats = $videoHandler->process();


										$file = new File();
										$file->setFilePath('uploads/'.$newPath);

										$file->setSmall($resized['small']);
										$file->setThumb($resized['thumb']);
										$file->setLong($resized['long']);
										$file->setBig($resized['big']);
										$file->setNewsroom($resized['newsroom']);
										$file->setDeviceImage($resized['device']);

										$file->setPost($post);
										$this->doctrine->em->persist($file);
										$post->addFiles($file);
										$post->setPostStatus("PROCESSING");
										$this->doctrine->em->persist($post);
										$this->doctrine->em->flush();

										$toProcess = new ToProcess();
										$toProcess->setFile($file);
										$toProcess->setUserName($userName);
										$toProcess->setVidPath($newPath);

										$this->doctrine->em->persist($toProcess);
										$this->doctrine->em->flush();

										//Add notification for user - "Your video is being processed"
										// $notif = new UserNotifications();
										// $notif->setNotifyText("Your video post is being processed and will be published soon");
										// $notif->setLink("#");
										// $notif->setUser($user);
										// $notif->setImage(base_url($resized['thumb']));
										// $notif->setActionType('POST');
										// $notif->setActionId($post->getId());

										// $this->doctrine->em->persist($notif);	
										// $this->doctrine->em->flush();
									}
								}

							}
						}
						if($title != null){
							$post->setHeadline(strip_tags($title));

						}else{
							$post->setHeadline('');
						}
						if($description != null){
							$post->setDescription(strip_tags($description));
						}else{
							$post->setDescription('');
						}

						$userImpact = $this->impactRepository->findImpact($_POST['impact']);

						if($userImpact){
							$post->setUserImpact($userImpact);
						}else{
							$post->setUserImpact(null);
						}

						$category = $this->categoryRepository->getCategory($_POST['category']);

						if($category){
							$post->setCategory($category);
						}else{
							$post->setCategory(null);
						}

						$user->setHasSeen(1);

						if($type == "user"){
							$post->setAuthor($user);
						}elseif ($type == "anonymous") {
							$post->setAuthor($user);
						}

						if(isset($source)){
							$post->setSourceOfMedia(strip_tags($source));
						}

						$post->setAuthor($user);

						$_POST['hashtags'] = json_decode($_POST['hashtags']);

						if(null != $_POST['hashtags']){

							$hashtags1 = $_POST['hashtags'];

							if(!$post->getHashtags()->isEmpty()){
								$post->getHashtags()->clear();
							}
							$this->doctrine->em->persist($post);


							foreach ($hashtags1 as $hashtag) {
								$hash = $this->hashtagRepository->checkHashtag(strip_tags($hashtag));

								if($hash){
									 if(isset($_POST['id'])) {
									 	
									// 	if("" != $_POST['id']){
									// 		foreach ($post->getHashtags() as $hasht) {

									// 			if($hasht->getHashtag() != $hash){
									// 				$post->addHashTag($hash);
									// 				$count = $hash->getHashtagUseCount();
													
									// 				if(null == $count){
									// 					$hash->setHashtagUseCount(1);
									// 				}else{
									// 					$hash->setHashtagUseCount(($count + 1));
									// 				}
									// 				$this->doctrine->em->persist($hash);
									// 				$this->doctrine->em->persist($post);
									// 			}
									// 		}
									// 	}else{

									// 		$post->addHashTag($hash);
									// 		$count = $hash->getHashTagUseCount();
											
									// 		if(null == $count){
									// 			$hash->setHashtagUseCount(1);
												
									// 		}else{
												
									// 			$hash->setHashtagUseCount(($count + 1));
									// 		}
									// 		$this->doctrine->em->persist($hash);
									// 		$this->doctrine->em->persist($post);
									// 	}
									
									 	

										$post->addHashTag($hash);
										$count = $hash->getHashTagUseCount();
										
										if(null == $count){
											$hash->setHashtagUseCount(1);
											
										}else{
											
											$hash->setHashtagUseCount(($count + 1));
										}
										$this->doctrine->em->persist($hash);
										$this->doctrine->em->persist($post);
									}

								}else{
									
									$hash1 = new Hashtags();
									$hash1->setHashtag(strip_tags($hashtag));
									$hash1->setHashtagUseCount(1);
									$this->doctrine->em->persist($hash1);
									$post->addHashTag($hash1);
									$this->doctrine->em->persist($post);
								}

							}
						}

						// echo "<pre>";
						// print_r($_POST['location']);
						// die();

						if(isset($_POST['location']) && null != $_POST['location']) {
							
							$coOrds = array();

							$coOrds = explode(',', $_POST['location']);

							$reverse = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coOrds[0].",".$coOrds[1]);
							$reverse = json_decode($reverse,true);

							$data = array();

							$data = $reverse['results'][0]['address_components'];

							$formatted_address = $reverse['results'][0]['formatted_address'];

							$locality = "";
							$administrative = "";

							foreach ($data as $d) {
								if ($d['types'][0] == "locality" && $d['types'][1] == "political") {
									$locality = $d['long_name'];
									$post->setLocality($locality);
								}

								if($d['types'][0] == "administrative_area_level_2" && $d['types'][1] == "political"){
									$administrative = $d['long_name'];
									$post->setCity($administrative);
								}

								if($d['types'][0] == "administrative_area_level_1" && $d['types'][1] == "political"){
									$state = $d['long_name'];
									$post->setState($state);
								}

								if($d['types'][0] == "country" && $d['types'][1] == "political"){
									$country = $d['long_name'];
									$post->setCountry($country);
								}
							}

							if($post->getLocation() == ","){
								$post->setLocation(NULL);
							}else{
								$post->setLocation($formatted_address);
							}

							$post->setLatitude($coOrds[0]);

							$post->setLongitude($coOrds[1]);
						}

						$this->doctrine->em->persist($post);

						$this->doctrine->em->flush();

						$slugCorrection = $post->getSlug()."-".$post->getId();

						$post->setSlug($slugCorrection);

						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						// $correctNotification = $this->doctrine->em->getRepository('Entity\UserNotifications')->findOneBy(array('actionId'=>$post->getId()));

						// $correctNotification->setLink(base_url()."single/".$post->getSlug());

						// $this->doctrine->em->persist($correctNotification);
						// $this->doctrine->em->flush();

						$followers = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('author'=>$post->getAuthor()));

						if(!is_null($followers) && ($post->getPostType() == "Image") && (!$post->getIsAnonymous())){

							$gcmIds = array();

							foreach ($followers as $follower) {

								$notification = new UserNotifications();
								$notification->setNotifyText($post->getAuthor()->getUserName()." has uploaded a new Post");
								$notification->setLink(base_url()."single/".$post->getSlug());
								$notification->setUser($follower->getUser());
								$notification->setImage(($post->getAuthor()->getProfilePicturePath()));
								$notification->setActionType('post');
								$notification->setActionId($post->getId());

								if(!in_array($post->getAuthor()->getGcmId(), $gcmIds)){
									$gcmIds[] = $post->getAuthor()->getGcmId();

									if(null != $post->getAuthor()->getGcmId()){
										$gcm = new GCM();

										$message = array(
											'msg'=>$post->getAuthor()->getUserName()." has uploaded a new Post",
											'action'=> array(
												'type'=>'post',
												'id'=>$post->getId()
											)
										);

										header("Content-Type: application/json");
										//$message = json_encode($message);

										$result = $gcm->send_notification(array($post->getAuthor()->getGcmId()),$message);
									}
								}


								$this->doctrine->em->persist($notification);
								$this->doctrine->em->flush();
							}
						}

						$response->setSuccess(true);
						$response->setData(array('msg'=>'Success','postType'=>$post->getPostType()));
						$response->setError('');
						// if ($draft == true) {
						// 	redirect('drafts');
						// } else {
		 			// 		redirect('post?upload=success');
						// }
					} catch (Exception $e) {
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>$e->getMessage()));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'User must be logged in!'));
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

	function detail(){

		$slug = $this->uri->segment(2);
		try {
			if(!empty($slug)){
				
				$post = $this->postRepository->getPostBySlug($slug);

				$postDetails = null;
				$data = array();

				if(null != $post && $post->getPostStatus() == "PUBLISHED"){
					session_start();

					if(!isset($_SESSION['P'.$post->getId().''])){

						$_SESSION['P'.$post->getId().''] = 1;

						if(null != $post->getPostDetails()){
							$postDetails = $post->getPostDetails();
							$views = $postDetails->getNumberOfViews();
							$postDetails->setNumberOfViews($views + 1);
							$data['rating'] = round($post->getPostDetails()->getRating(),1);
							$this->doctrine->em->persist($postDetails);
							$this->doctrine->em->flush();
						}elseif(null == $post->getPostDetails()) {
							$postDetails = new PostDetails();
							$postDetails->setPost($post);
							$postDetails->setNumberOfViews(1);
							$this->doctrine->em->persist($postDetails);
							$post->setPostDetails($postDetails);
							$this->doctrine->em->persist($post);
							$this->doctrine->em->flush();

							$data['rating'] = 0;
						}
					}

					$data['id'] = $post->getId();
					if($post->getIsAnonymous() == true){
						$data['author'] = "Anonymous";
					}else{
						$data['author'] = $post->getAuthor()->getUserName();	
					}

					$data['slug'] = $post->getSlug();
					
					$data['description'] = $post->getDescription();
					$data['title'] = $post->getHeadline();
					$date = $post->getUpdatedOn();
					$date = $date->format('d-M-Y');
					$data['date'] = $date;
					$data['postType'] = $post->getPostType();
					$data['views'] = $post->getPostDetails()->getNumberOfViews();
					$data['impact'] = $post->getUserImpact()->getArea();
					$data['location'] = $post->getLocation();

					$data['file'] = array();
					foreach ($post->getFiles() as $file) {
						//$data['file'][] = $file->getFilePath();
						$tmpFiles = array();
						$tmpFiles['share'] = $file->getFilePath();
						$tmpFiles['bigImage'] = base_url($file->getBig());
						$tmpFiles['mp4'] = base_url($file->getMp4());
						$tmpFiles['ogg'] = base_url($file->getOgg());
						$tmpFiles['webm'] = base_url($file->getWebm());
						$data['file'][] = $tmpFiles;
						//break;
					}

					if(null != $post->getSharedCount()){
						$data['numberOfShares'] = $post->getSharedCount();
					}else{
						$data['numberOfShares'] = 0;
					}

					$data['hashtags'] = array();
					foreach ($post->getHashTags() as $hashtag) {
						$data['hashtags'][] = $hashtag->getHashtag();
					}

					$data['postStatus'] = $post->getPostStatus();

					if($post->getSourceOfMedia() == ("self") || $post->getSourceOfMedia() == null || $post->getSourceOfMedia() == ""){
						$data['source'] = "Self";
					}else{
						 $data['source'] = $post->getSourceOfMedia();
					}

					$data['rating'] = 0;
					if(!is_null($post->getPostDetails())){
						if(null != $post->getPostDetails()->getRating()){
							$data['rating'] = round($post->getPostDetails()->getRating(),2) * 10;
						}
					}


					$flags = $this->doctrine->em->getRepository('Entity\FlagLog')->findBy(array('post'=>$post));

					$data['flags'] = "";

					if(!is_null($flags)){
						$data['flags'] = count($flags);
					}

					if($post->getIsFeatured() == 1){
						$data['isFeatured'] = true;
					}else{
						$data['isFeatured'] = false;
					}

					$user = isUserLoggedIn();

					if(false != $user){

						$profile['userName'] = $user->getUserName();
						$profile['picture'] = $user->getProfilePicturePath();

						$this->load->view('header',array('data'=>$profile,'isSingle'=>true,'postData'=>$data));
						// $this->load->view('user/header');
						$this->load->view('navigation');
						$this->load->view('user/singlePost',array('postData'=>$data));
						$this->load->view('footer',array('scripts'=>array('controllers/singlePageController.js', 'bxslider/jquery.bxslider.min.js','slider.js'), 'styles' => array('js/bxslider/jquery.bxslider.css')));
					}else{

						$this->load->view('header',array('isSingle'=>true,'postData'=>$data));
						$this->load->view('navigation');
						$this->load->view('user/singlePost',array('postData'=>$data));
						$this->load->view('footer',array('scripts'=>array('controllers/singlePageController.js', 'bxslider/jquery.bxslider.min.js','slider.js'), 'styles' => array('js/bxslider/jquery.bxslider.css')));
					}
				}else{
					redirect('error?id=1');
				}
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

	// function getPostforHahstags(){
	// 	$this->postRepository->getPostsByHashtag("tag3");
	// }

	function showPostsList(){
		try {
			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();

				$email = $user->getEmail();
				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$profile));
				// $this->load->view('user/header');
				$this->load->view('user/sidebar');
				$this->load->view('user/postsList');
				$this->load->view('footer',array('scripts'=>array('validations/imageCorrector.js','controllers/postsListPagecontroller.js')));

			}else{
				redirect('/?sessionexpired');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}



	function getAllPosts(){
		session_start();
		$response = new Response();

		if (isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
			$user = null;
			if(isset($_GET['userId'])){
				$user = $this->userRepository->getUser($_GET['userId']);	
			}else{
				$email = $_SESSION['email'];

				$user = $this->userRepository->checkUser($email);
			}
			if($_SERVER['REQUEST_METHOD'] == "GET"){

				$posts = $this->postRepository->getAllPostsForUser($user);

				if($posts){
					$data = array();
					foreach ($posts as $post) {
						$temp['id'] = $post->getId();
						$temp['title'] = $post->getHeadline();
						$date = $post->getUpdatedOn();
						$date = $date->format('d-M-Y');
						$temp['date'] = $date;
						$hashtags = $post->getHashTags();
						
						$temp['hashtags'] = array();

						foreach ($hashtags as $hashtag) {
							$temp['hashtags'][] = $hashtag->getHashtag();
						}

						$files = $post->getFiles();

						foreach ($files as $file) {
							$temp['file'] = base_url().$file->getThumb();
							break;
						}

						$temp['postType'] = $post->getPostType();
						$temp['slug'] = $post->getSlug();

						$temp['rating'] = 0;
						if(!is_null($post->getPostDetails())){
							if(null != $post->getPostDetails()->getRating()){
								$temp['rating'] = round($post->getPostDetails()->getRating(),2) * 10;
							}
						}

						$data[] = $temp;
					}

					$response->setSuccess(true);
					$response->setData(array('posts'=>$data));
					$response->setError('');
				} else {
					$response->setSuccess(false);
					$response->setError(array('msg'=>'No Posts for User'));
				}
			}else{
				$response->setSuccess(false);
				$response->setError(array('msg'=>'Method Error'));
			}
		} else {
			$response->setSuccess(false);
			$response->setError(array('msg'=>'Session Expired'));
		}

		$response->respond();
		die();
	}

	function deletePosts(){
		if($_SERVER['REQUEST_METHOD'] == "GET"){
			$response = new Response();
			$id = json_decode($_GET['id']);

			$post = $this->postRepository->getPost($id);
			if($post){
				$this->doctrine->em->remove($post);
				$this->doctrine->em->flush();


				$response->setSuccess(true);
				$response->setData(array('msg'=>'deleted'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setError(array('msg'=>'post not found'));
			}

		}else{
			$response->setSuccess(false);
			$response->setError(array('msg'=>'Method Error'));
		}

		$response->respond();
		die();
	}

	function unpublishPosts(){
		
		if($_SERVER['REQUEST_METHOD'] == "GET"){
			$response = new Response();
			$id = json_decode($_GET['id']);

			$post = $this->postRepository->getPost($id);

			if(null != $post){
				$post->setPostStatus("DRAFT");
				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'unpublished'));
				$response->setError('');
					// redirect('post?published');
			}else{
				$response->setSuccess(false);
				$response->setError(array('msg'=>'post not found'));
			}

		}else{
			$response->setSuccess(false);
			$response->setError(array('msg'=>'Method Error'));
		}

		$response->respond();
		die();
	}

	function getRecentPosts(){
		session_start();

		$response = new Response();

		if(isset($_SESSION['id'])){

			$email = $_SESSION['email'];

			$user = $this->userRepository->checkUser($email);

			if($_SERVER['REQUEST_METHOD'] == "GET"){
				$howMany = 0;
				if(isset($_GET['howMany'])){
					$howMany = $_GET['howMany'];
				}else{
					$howMany = 6;
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
						
						foreach ($post->getFiles() as $file) {
							$temp['file'] = $file->getThumb();
						}
						

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
		} else {
			$response->setSuccess(false);
			$response->setError(array('msg'=>'Session Expired'));
		}

		$response->respond();
		die();
	}

	function getRecords(){
		$response = new Response();

		$posts = $this->postRepository->getAllRecords(4);

		if($posts){
			$data = array();
			foreach ($posts as $post) {
				$temp['id'] = $post->getId();
				$temp['title'] = $post->getHeadline();
				$temp['date'] = $post->getUpdatedOn()->format('d-M-Y');
				if($post->getIsAnonymous() == false){
					$temp['author'] .= " ".$post->getAuthor()->getUserName();	
				}else{
					$temp['author'] == "Anonymous";
				}
				
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
				if(null != $post->getFiles()){
					foreach ($post->getFiles() as $file) {
						if(null != $file){
							$temp['file'] = $file->getFilePath();
						}else{
							$temp['file'] = null;
						}

						break;
					}
				}else{
					$temp['file'] = null;
				}

				$temp['postType'] = $post->getPostType();

				$data[] = $temp;
			}

			$response->setSuccess(true);
			$response->setData($data);
			$response->setError('');
		}else{

		}
		$response->respond();
		die();

	}

	function single(){
		$slug = $this->uri->segment(2);
		try {
			$post = $this->postRepository->getPostBySlug($slug);
			if($post) {
				echo $post->getHeadline();
			} else {
				echo "not found";
			}
		} catch (Exception $e) {
			
		}
	}

	function getPost(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$_POST = file_get_contents("php://input");

				$post = $this->postRepository->getPost($_POST);

				if(false != $post){
					$data = array();

					$data['id'] = $post->getId();
					$data['title'] = $post->getHeadline();
					$data['category'] = $post->getCategory()->getId();
					$data['impact'] = $post->getUserImpact()->getId();
					$data['description'] = $post->getDescription();

					$data['hashtags'] = array();
					foreach ($post->getHashtags() as $hashtag) {
						// $temp['id'] = $hashtag->getId();
						// $temp['hashtag'] = $hashtag->getHashtag();

						$data['hashtags'][] = $hashtag->getHashtag();
					}

					$data['files'] = array();
					$i = 0;
					foreach ($post->getFiles() as $file) {
						$tmp['id'] = $file->getId();
						$tmp['file'] = $file->getFilePath();
						$tmp['serial'] = $i;
						$i++;

						$data['files'][] = $tmp;
					}

					if($post->getIsAnonymous() == true){
						$data['type'] = "anonymous";
						$data['individual'] = false;
					}else{
						$data['type'] = "user";
						$data['individual'] = true;
					}

					$data['address'] = $post->getLocation();

					$data['latitude'] = $post->getLatitude();
					$data['longitude'] = $post->getLongitude();

					$data['postType'] = $post->getPostType();
					$data['source'] = $post->getSourceOfMedia();
				
					$response->setSuccess(true);
					$response->setData($data);
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'Post not found'						
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
		$response->respond();
		die();
	}

	function flagContent(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(false != isUserLoggedIn()){
					$user = isUserLoggedIn();

					$_POST = file_get_contents("php://input");
					$_POST = json_decode($_POST);
					$_POST = get_object_vars($_POST);
					
					$post = $this->postRepository->getPost($_POST['id']);

					if(false != $post){
						$flagLog = new FlagLog();
						$flagLog->setPost($post);
						$flagLog->setUser($user);
						$flagLog->setReason($_POST['reason']);

						$this->doctrine->em->persist($flagLog);
						$this->doctrine->em->flush();

						$flags = $this->doctrine->em->getRepository('Entity\FlagLog')->findBy(array('post'=>$post));

						$count = 0;
						if(null != $flags){
							$count = count($flags);
							
							if($count > 25){
								$post->setPostStatus("Banned");

								$this->doctrine->em->persist($post);
								$this->doctrine->em->flush();
							}
						}

						$response->setSuccess(true);
						$response->setData(array(
							'msg'=>'Flag Recorded'
						));
						$response->setError('');

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'msg'=>'Post not found'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'User not logged in'
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

		$response->respond();
		die();
	}

	function checkFlag(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(false != isUserLoggedIn()){
					$user = isUserLoggedIn();

					$_POST = file_get_contents("php://input");
					$_POST = json_decode($_POST);

					$post = $this->postRepository->getPost($_POST);

					if(false != $post){
						$flagLog = $this->doctrine->em->getRepository('Entity\FlagLog')->findOneBy(array('post'=>$post,'user'=>$user));

						if(null != $flagLog){
							$response->setSuccess(true);
							$response->setData(array('msg'=>'You have already Flagged the post'));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'msg'=>'Not flagged'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'msg'=>'Post not found'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
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
			$response->setError(array(
				'msg'=>$e->getMessage()
			));
		}
		$response->respond();
		die();
	}

	function isFeatured(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$_POST = file_get_contents("php://input");

				$post = $this->postRepository->getPost($_POST);

				if(false != $post){
					if($post->getIsFeatured() == 1){
						$response->setSuccess(true);
						$response->setData(array(
							'msg'=>'Featured'
							));
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'msg'=>'Not Featured'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'Post Not found'
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
			$response->setError(array(
				'msg'=>$e->getMessage()
			));
		}
		$response->respond();
		die();
	}

	function anonymousPage(){
		try {
			if(false != isUserLoggedIn()){

				$user = isUserLoggedIn();

				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$profile));
				// $this->load->view('user/header');
				$this->load->view('navigation');
				$this->load->view('pages/anonymous');
				$this->load->view('footer',array('scripts'=>array('controllers/anonymousPageController.js')));

			}else{
				$this->load->view('header');
				$this->load->view('navigation');
				$this->load->view('pages/anonymous');
				$this->load->view('footer',array('scripts'=>array('controllers/anonymousPageController.js')));
			}
		} catch (Exception $e) {
			redirect('technicalProblem');			
		}
	}

	function anonymousPosts(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isAnonymous'=>1,'postStatus'=>'PUBLISHED'));

				if(!is_null($posts)){
					$data = array();
					foreach ($posts as $post) {
						$temp['id'] = $post->getId();
						$temp['title'] = $post->getHeadline();
						$temp['description'] = $post->getDescription();
						$date = $post->getUpdatedOn();
						$date = $date->format('d-M-Y H:i:s');
						$temp['date'] = $date;
						$temp['author'] = "Anonymous";
						$temp['hashtags'] = array();

						foreach ($post->getHashtags() as $hashtag) {
							$temp['hashtags'][] = $hashtag->getHashtag();
						}

						// $temp['files'] = array();

						foreach ($post->getFiles() as $file) {
							// $tempFile = array();
							// $tempFile['thumb'] = 
							// $tempFile['small'] = base_url($file->getSmall());
							$temp['files'] = base_url($file->getThumb());
							break;
						}

						$temp['slug'] = $post->getSlug();

						if($post->getPostType() == "Video"){
							$temp['showVideo'] = true;
						}else{
							$temp['showVideo'] = false;
						}

						$data[] = $temp;
					}

					$response->setSuccess(true);
					$response->setData(array(
						'data'=>$data
					));
					$response->setError(null);

				}else{
					$response->setSuccess(false);
					$response->setData(null);
					$response->setError(array(
						'code'=>'',
						'msg'=>'No posts to display'
					));
				}
			} catch (Exception $e) {
				// redirect('technicalProblem');
				$response->setSuccess(false);
				$response->setData(null);
				$response->setError(array(
					'code'=>'',
					'msg'=>$e->getMessage()
				));
			}
		}else{
			$response->setSuccess(false);
			$response->setData(null);
			$response->setError(array(
				'code'=>401,
				'msg'=>'Cross domain requests are not allowed'
			));
		}

		$response->respond();
		die();
	}

	function getRelatedPosts(){
		session_start();

		$response = new Response();

		if(isset($_SESSION['id'])){

			$email = $_SESSION['email'];

			$user = $this->userRepository->checkUser($email);

			if($_SERVER['REQUEST_METHOD'] == "GET"){
				$howMany = 0;
				if(isset($_GET['howMany'])){
					$howMany = $_GET['howMany'];
				}else{
					$howMany = 6;
				}
				$posts = $this->postRepository->getRelatedPostsByUser($email, $howMany, $_GET['categId']);

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
						if(null != $post->getFiles()){
							foreach ($post->getFiles() as $file) {
								if(null != $file){
									$temp['file'] = $file->getFilePath();
								}else{
									$temp['file'] = null;
								}

								break;
							}
						}else{
							$temp['file'] = null;
						}

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
		} else {
			$response->setSuccess(false);
			$response->setError(array('msg'=>'Session Expired'));
		}

		$response->respond();
		die();		
	}

	function getImpacts(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "GET"){
				$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();

				if(!is_null($impacts)){
					$data = array();
					foreach ($impacts as $impac) {
						$temp['id'] = $impac->getId();
						$temp['area'] = $impac->getArea();

						$data[] = $temp;
					}

					$response->setSuccess(true);
					$response->setData(array('impacts'=>$data));
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'There are no impacts'
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
			$response->setError(array(
				'msg'=>$e->getMessage()
			));
		}
		$response->respond();
		die();
	}

	function removeFile(){
		$response = new Response();
		try {
			if(isUserLoggedIn()){
				$user = isUserLoggedIn();

				if($_SERVER['REQUEST_METHOD'] == "POST"){
					$_POST = file_get_contents("php://input");
					$_POST = json_decode($_POST);
					$_POST[1] = get_object_vars($_POST[1]);
				
					$post = $this->postRepository->getPost($_POST[0]);

					if($post){
						$file = $this->doctrine->em->getRepository('Entity\File')->findOneBy(array('id'=>$_POST[1]['id']));

						if($file){
							$this->doctrine->em->remove($file);
							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array('msg'=>'File Deleted'));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'msg'=>'File was not found'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'msg'=>'There was an error removing files. Please try again'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'Method Error'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'User Logged in'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'msg'=>$e->getMessage()
			));
		}
		$response->respond();
		die();
	}

	function makezero(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isFeatured'=>null));

		foreach ($posts as $post) {
			$post->setIsFeatured(0);
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";
	}

	function makeAnonymous(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('postStatus'=>'Anonymous'));

		foreach ($posts as $post) {
			$post->setIsAnonymous(1);
			$post->setPostStatus('PUBLISHED');
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";
	}

	function makeFeaturedZero(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isFeatured'=>null));

		foreach ($posts as $post) {
			$post->setIsFeatured(0);
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";	
	}


	function makeBreakingZero(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isBreaking'=>null));

		foreach ($posts as $post) {
			$post->setIsBreaking(0);
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";	
	}


	function makeShareZero(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('sharedCount'=>null));

		foreach ($posts as $post) {
			$post->setSharedCount(0);
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";	
	}


	function makeTrendingZero(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isTrending'=>null));

		foreach ($posts as $post) {
			$post->setIsTrending(0);
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";	
	}

	function correctPostStatus(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('postStatus'=>'Publish'));

		foreach ($posts as $post) {
			$post->setPostStatus("PUBLISHED");
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";	
	}

	function correctPostDraft(){
		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('postStatus'=>'Draft'));

		foreach ($posts as $post) {
			$post->setPostStatus("DRAFT");
			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
		}

		echo "string";	
	}

	// function randomString(){
	// 	$characters = "I am sexy and I know it";
	// 	$randomString = md5($characters);
	// 	echo $randomString;
	// }
}
