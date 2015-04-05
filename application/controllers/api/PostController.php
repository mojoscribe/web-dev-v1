<?php
use Entity\Post;
use Entity\File;
use Entity\Hashtags;
use Entity\ToProcess;
use Entity\UserNotifications;

class PostController extends CI_Controller {
	var $postRepository;
	var $categoryRepository;
	var $impactRepository;
	var $hashtagRepository;
	function __construct(){
		parent::__construct();
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->impactRepository = $this->doctrine->em->getRepository('Entity\Impact');
		$this->categoryRepository = $this->doctrine->em->getRepository('Entity\Category');
		$this->hashtagRepository = $this->doctrine->em->getRepository('Entity\Hashtags');
		$this->load->file('application/classes/Response.php');
		$this->load->file('application/classes/VideoHandler.php');
		$this->load->file('application/classes/ImageHandler.php');
		$this->load->file('application/classes/GCM.php');
		$this->load->helper('date');
		$this->load->helper('api');
		$this->load->helper('file');
	}

	function index(){
		$response = new Response();
		if(checkApiKey()){

			if($_SERVER['REQUEST_METHOD'] == "POST"){

				$headers = apache_request_headers();
				if(isset($headers['auth-token'])){

					$user = checkAuthToken();

					if(false != $user){
						extract($_POST);

						$post = "";
						if("" != $_POST['id'] || !isset($_POST['id'])){
							$post = $this->postRepository->getPost($_POST['id']);
							if($_POST['postType'] == "Image"){
								$post->setPostStatus("PUBLISHED");
							}else{

								if(!$post->getFiles()->isEmpty()){

									$userName = "";

									$file = $post->getFiles();
									$file = $file[0];

									$filePa = explode('/', $file->getFilePath());

									if($userType == "user"){
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
						}

						$post->setPostStatus("PUBLISHED");

						if($userType == "user"){
							$post->setIsAnonymous(false);
						}elseif($userType == "anonymous"){
							$post->setIsAnonymous(true);
						}

						$post->setHeadline($headline);
						
						if(null != $description){
							$post->setDescription($description);
						}

						$category = $this->categoryRepository->getCategory($_POST['category']);
						if(false != $category){
							$post->setCategory($category);	
						}else{
							$post->setCategory(null);
						}

						$userImpact = $this->impactRepository->findImpact($_POST['impact']);
						if(false != $userImpact){
							$post->setUserImpact($userImpact);
						}else{
							$post->setUserImpact(null);
						}

						if($_POST['postType'] == "Image"){
							$post->setPostType($postType);

							$file_ary = deviceArrayFiles($_FILES['images']);

							foreach ($file_ary as $image) {

								$_FILES['images']['name']= $image['name'];
						        $_FILES['images']['type']= $image['type'];
						        $_FILES['images']['tmp_name']= $image['tmp_name'];
						        $_FILES['images']['error']= $image['error'];
						        $_FILES['images']['size']= $image['size'];
								$config ['upload_path'] = './uploads/';
								$config ['allowed_types'] = 'jpeg|png|jpg';
						        $config ['max_size'] = '30000'; //30MB  /*What is the limit?*/

								$this->load->library ( 'upload', $config );

								if (!$this->upload->do_upload('images')) {
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

									if($userType == "user"){
										// TODO: Watermark user name  
										$imageHandler->waterMark($user->getUserName(), $data['file_name']);
									}

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
						}else{
							$post->setPostType($postType);

							$config ['upload_path'] = './uploads/';
							$config ['allowed_types'] = 'avi|mp4|3gp|mpg';
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

								rename('/var/www/html/uploads/'.$data['file_name'], '/var/www/html/uploads/'.$newPath);

								$videoHandler = new VideoHandler($newPath);
								$resized = $videoHandler->generateThumbnail();

								$userName = "";
								if($userType == "user"){
									// TODO: Watermark user name 
									$videoHandler->waterMarkImage($user->getUserName());
									$userName = $user->getUserName();
								}

								$file = new File();
								$file->setFilePath(('uploads/'.$newPath));

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
								$notif = new UserNotifications();
								$notif->setNotifyText("Your video post is being processed and will be published soon");
								$notif->setLink("#");
								$notif->setUser($user);
								$notif->setImage(base_url($resized['thumb']));
								$notif->setActionType('POST');
								$notif->setActionId($post->getId());

								$this->doctrine->em->persist($notif);
								$this->doctrine->em->flush();
							}
						}


						$post->setAuthor($user);

						/*echo "<pre>";
						print_r($user->getId());
						die();*/

						// $latitude = $_POST['latitude'];
						// $longitude = $_POST['longitude'];

						// $location = $latitude.",".$longitude;

						// $post->setLocation($_POST['position']);


						if(isset($_POST['position'])){
							
							$coOrds = array();

							$coOrds = explode(',', $_POST['position']);

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

						if(null != $_POST['hashtags']){
							$words = explode(" ", trim($hashtags));

							$spaces=array();
							$hashtags1=array();

							foreach($words as $word) {
								if($word==' '){
									array_push($spaces,$word);
								}else{
									array_push($hashtags1,$word);
								}
							}
							// $hashtags1 = $_POST['hashtags'];

							foreach ($hashtags1 as $hashtag) {
								$hash = $this->hashtagRepository->checkHashtag($hashtag);

								if($hash){

									if("" != $_POST['id']){
										foreach ($post->getHashtags() as $hasht) {

											if($hasht->getHashtag() != $hash->getHashtag()){
												$post->addHashTag($hash);
												$count = $hash->getHashtagUseCount();
												
												if(null == $count){
													$hash->setHashtagUseCount(1);
												}else{
													$hash->setHashtagUseCount(($count + 1));
												}
												$this->doctrine->em->persist($hash);
												$this->doctrine->em->persist($post);
											}
										}

									}else{

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
									$hash1->setHashtag($hashtag);
									$hash1->setHashtagUseCount(1);
									$this->doctrine->em->persist($hash1);
									$post->addHashTag($hash1);
									$this->doctrine->em->persist($post);

								}

							}
						}


						$slug = $this->postRepository->createSlug($headline);

						$post->setSlug($slug);

						if($source == "user"){
							$post->setSourceOfMedia("Self");
						}else{
							$post->setSourceOfMedia($source);
						}

						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						$slugCorrection = $post->getSlug()."-".$post->getId();

						$post->setSlug($slugCorrection);

						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						$followers = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('author'=>$post->getAuthor()));

						if(!is_null($followers) && $post->getPostType() == "Image" &&(!$post->getIsAnonymous())){
							foreach ($followers as $follower) {

								$notification = new UserNotifications();
								$notification->setNotifyText($post->getAuthor()->getUserName()." has uploaded a new Post");
								$notification->setLink(base_url()."single/".$post->getSlug());
								$notification->setUser($follower->getUser());
								$notification->setImage(($post->getAuthor()->getProfilePicturePath()));
								$notification->setActionType('post');
								$notification->setActionId($post->getId());

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

								$this->doctrine->em->persist($notification);
								$this->doctrine->em->flush();
							}
						}

						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						$response->setSuccess(true);
						$response->setData(array('msg'=>'Post Uploaded'));
						$response->setError('');					

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'2002',
							'msg'=>'User does not exist'));				
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'2005',
						'msg'=>'Auth Token not set'
						));
				}
			}else{
				$response->setSuccess(false);
				$response->setData(null);
				$response->setError(array(
					'code'=>'1100',
					'msg'=>'Method Error'));	
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'code'=>'1099',
				'msg'=>'Invalid API Key'));
		}

		$response->respond();
		die();
	}

	function postsList(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$headers = apache_request_headers();
				if(isset($headers['api-key'])){
					if(checkApiKey()){
						if(isset($headers['auth-token'])){
							$user = checkAuthToken();
							if(false != $user){
								$posts = $this->postRepository->getAllPostsForUser($user);

								if(null != $posts){
									$data = array();

									foreach ($posts as $post) {
										$temp['id'] = $post->getId();
										$temp['headline'] = $post->getHeadline();										
										// $temp['author'] .= " ".$post->getAuthor()->getLastName();

										// $date = checkDateTimeDiff($post->getUpdatedOn());
										$temp['updatedOn'] = $post->getUpdatedOn()->format('d-M-Y');

										$temp['files'] = array();
										foreach ($post->getFiles() as $file) {
											$tmp['id'] = $file->getId();
											if($file->getBig()){
												$tmp['file'] = base_url().$file->getDeviceImage();
											}else{
												$tmp['file'] = base_url();
											}
											$temp['files'][] = $tmp;
										}

										$temp['type'] = $post->getPostType();

										$data[] = $temp;
									}

									$response->setSuccess(true);
									$response->setData($data);
									$response->setError('');

								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'4002',
										'msg'=>'No posts for this user'
										));
								}
							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array(
									'code'=>'2002',
									'msg'=>'User does not exist'
									));
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'2005',
								'msg'=>'Auth Token not set'
								));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=> 1099 ,
							'msg' => 'Invalid API Key'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>1098,
						'msg'=>'API key not set'
						));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'1100',
					'msg'=>'Method Error'
					));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Oops! Something went wrong! We are looking into it!'));	
		}

		$response->respond();
		die();
	}


	function deletePosts(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(checkApiKey()){
					if(isset($headers['auth-token'])){
						if(checkAuthToken()){
							$user = checkAuthToken();
							if($_SERVER['REQUEST_METHOD'] == "POST"){
								if(isset($_POST['postIds'])) {
									// $_POST =  json_decode($_POST);
									$_POST['postIds'] = explode(',', $_POST['postIds']);
									array_pop($_POST['postIds']);

									// echo "<pre>";
									// print_r($_POST['postIds']);
									// die();

									foreach ($_POST['postIds'] as $postId) {

										$post = $this->postRepository->getPost($postId);

										if(false != $post && $post->getAuthor()->getId() == $user->getId()){

											$post->setPostStatus('UNPUBLISHED');

											$this->doctrine->em->persist($post);
											// $this->doctrine->em->remove($post);

											$this->doctrine->em->flush();
										}else{
											$response->setSuccess(false);
											$response->setData('');
											$response->setError(array(
												'code'=>'',
												'msg'=>$post->getId().",".$post->getAuthor()->getUserName()
											));
											$response->respond();
											die();
										}
									}

									$response->setSuccess(true);
									$response->setData(array(
										'msg'=>'Posts deleted'
									));
									$response->setError('');
								} else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'',
										'msg'=>'Post Ids not sent in the request'
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
								'code'=>2002,
								'msg'=>'Auth Token mismatch'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>2005,
							'msg'=>'Auth Token not set'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>1099,
						'msg'=>'Invalid Api Key'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>1098,
					'msg'=>'Api Key not set'
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

	function unPublishPosts(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(checkApiKey()){
					if(isset($headers['auth-token'])){
						if(checkAuthToken()){
							if($_SERVER['REQUEST_METHOD'] == "POST"){
								if(isset($_POST['postIds'])){

									$_POST['postIds'] = explode(',', $_POST['postIds']);
									array_pop($_POST['postIds']);

									foreach ($_POST['postIds'] as $postId) {
										$post = $this->postRepository->getPost($postId);

										if(false != $post){
											$post->setPostStatus("DRAFT");
											$post->setSlug("");

											$this->doctrine->em->persist($post);
											$this->doctrine->em->flush();

										}else{
											$response->setSuccess(false);
											$response->setData('');
											$response->setError(array(
												'code'=>'',
												'msg'=>'Something went wrong while unpublishing the post'
											));

											$response->respond();
											die();
										}
									}

									$response->setSuccess(true);
									$response->setData(array(
										'msg'=>'Posts unpublished'
									));
									$response->setError('');

								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'',
										'msg'=>'Post Ids not set'
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
								'code'=>2002,
								'msg'=>'Auth token mismatch'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>2005,
							'msg'=>'Auth Token not set'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>1099,
						'msg'=>'Invalid Api Key'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>1098,
					'msg'=>'api-key not set'
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

	function getPrePostData(){

		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(checkApiKey()){
					if(isset($headers['auth-token'])){
						$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('authToken'=>$headers['auth-token']));

						if(null != $user){
							if($_SERVER['REQUEST_METHOD'] == "POST"){
								$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();

								$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();

								if(null != $categories && null != $impacts){
									$categoriesData = array();
									foreach ($categories as $category) {
										$temp['id'] = $category->getId();
										$temp['name'] = $category->getName();

										$categoriesData[] = $temp;
									}

									$impactsData = array();
									foreach ($impacts as $impact) {
										$temp1['id'] = $impact->getId();
										$temp1['area'] = $impact->getArea();

										$impactsData[] = $temp1;
									}

									$postsForUser = $this->postRepository->getPostsForUserHashtags($user);

									$hashtagData = array();
									if(null != $postsForUser){
										foreach ($postsForUser as $post) {
											if(null != $post->getHashtags()){
												foreach ($post->getHashtags() as $hashtag) {
													/*if(!in_array($hashtag->getId(), $hashtagData)){
														$temp2['id'] = $hashtag->getId();
														$temp2['hashtag'] = $hashtag->getHashtag();												
														
													$temp2 = array();													
													$temp2['id'] = $hashtag->getId();
													$temp2['hashtag'] = $hashtag->getHashtag();

													if(!in_array($temp2, $hashtagData)) {
														$hashtagData[] = $temp2;
													}*/
													$temp2 = array();													
													$temp2['id'] = $hashtag->getId();
													$temp2['hashtag'] = $hashtag->getHashtag();

													if(!in_array($temp2, $hashtagData)) {
														$hashtagData[] = $temp2;
													}
												}
											}
										}
									}

									$result = array();

									$result = array_unique($hashtagData,SORT_REGULAR);

									$response->setSuccess(true);
									$response->setData(array('categories'=>$categoriesData , 'impacts'=>$impactsData, 'hashtagSuggestions'=>$result));
									$response->setError('');

								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'8001',
										'msg'=>'No categories or impacts were found'
									));
								}
							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array(
									'code'=>'1100',
									'msg'=>'Method Error'
								));
							}

						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'2001',
								'msg'=>'Invalid auth-token'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'',
							'msg'=>'auth-token not set'
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
			$response->setError(array(
				'code'=>'',
				'msg'=>$e->getMessage()
			));
		}
		$response->respond();
		die();
	}

	function previewPost(){
		$response = new Response();
		if(checkApiKey()){

			if($_SERVER['REQUEST_METHOD'] == "POST"){

				$headers = apache_request_headers();
				if(isset($headers['auth-token'])){

					$user = checkAuthToken();

					if(false != $user){
						extract($_POST);

						$post = "";
						if("" != $_POST['id'] || !isset($_POST['id'])){
							$post = $this->postRepository->getPost($_POST['id']);
							if(!is_null($post)) {
								/*$response->setSuccess();
								$response->setData();
								$response->setError();*/
							} else {

							}
						}else{
							$post = new Post();
						}

						$post->setHeadline($headline);
						
						if(null != $description){
							$post->setDescription($description);
						}

						$category = $this->categoryRepository->getCategory($_POST['category']);
						if(false != $category){
							$post->setCategory($category);	
						}else{
							$post->setCategory(null);
						}

						$userImpact = $this->impactRepository->findImpact($_POST['impact']);
						if(false != $userImpact){
							$post->setUserImpact($userImpact);
						}else{
							$post->setUserImpact(null);
						}

						if($_POST['postType'] == "Image"){
							$post->setPostType($postType);

							$file_ary = deviceArrayFiles($_FILES['images']);

							foreach ($file_ary as $image) {

								$_FILES['images']['name']= $image['name'];
						        $_FILES['images']['type']= $image['type'];
						        $_FILES['images']['tmp_name']= $image['tmp_name'];
						        $_FILES['images']['error']= $image['error'];
						        $_FILES['images']['size']= $image['size'];
								$config ['upload_path'] = './uploads/';
								$config ['allowed_types'] = 'jpeg|png|jpg';
						        $config ['max_size'] = '30000'; //30MB  /*What is the limit?*/

								$this->load->library ( 'upload', $config );

								if (!$this->upload->do_upload('images')) {
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array('msg'=>$this->upload->display_errors()));

									$response->respond();
									die();

								} else {
									$data = $this->upload->data();
									// if($type == "user"){
									// 	$this->load->library('image_lib');

									// 	$config = array();
									// 	$config['source_image']	= 'uploads/'.$data['file_name'];
									// 	$config['wm_text'] = $user->getUserName();
									// 	$config['wm_type'] = 'text';
									// 	$config['wm_font_path'] = './system/fonts/texb.ttf';
									// 	$config['wm_font_size']	= '10';
									// 	$config['wm_font_color'] = 'ffffff';
									// 	$config['wm_vrt_alignment'] = 'bottom';
									// 	$config['wm_hor_alignment'] = 'left';

									// 	$this->image_lib->initialize($config); 

									// 	$this->image_lib->watermark();
									// }

									// 	$config['source_image']	= 'uploads/'.$data['file_name'];
									// 	$config['wm_type'] = 'overlay';
									// 	$config['wm_overlay_path'] = 'uploads/logo.png';
									// 	$config['wm_opacity'] = 100;
									// 	$config['wm_vrt_alignment'] = 'top';
									// 	$config['wm_hor_alignment'] = 'right';

									// 	$this->image_lib->initialize($config); 

									// 	$this->image_lib->watermark();

									$file = new File();
									$file->setFilePath(base_url('uploads/'.$data['file_name']));
									$file->setPost($post);
									$this->doctrine->em->persist($file);
									$post->addFiles($file);
									$this->doctrine->em->persist($post);
								}
							}
						}else{
							$config ['upload_path'] = './uploads/';
							$config ['allowed_types'] = 'avi|mp4|3gp|mpg';
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

								$file = new File();
								$file->setFilePath(base_url('uploads/'.$data['file_name']));
								$file->setPost($post);
								$this->doctrine->em->persist($file);
								$post->addFiles($file);
								$this->doctrine->em->persist($post);
							}
						}


						$post->setAuthor($user);

						if(null != $_POST['hashtags']){
							$words = explode(" ", trim($hashtags));

							$spaces=array();
							$hashtags1=array();

							foreach($words as $word) {
								if($word==' '){
									array_push($spaces,$word);
								}else{
									array_push($hashtags1,$word);
								}
							}
							// $hashtags1 = $_POST['hashtags'];

							foreach ($hashtags1 as $hashtag) {
								$hash = $this->hashtagRepository->checkHashtag($hashtag);

								if($hash){

									if("" != $_POST['id']){
										foreach ($post->getHashtags() as $hasht) {

											if($hasht->getHashtag != $hash){
												$post->addHashTag($hash);
												$count = $hash->getHashtagUseCount();
												
												if(null == $count){
													$hash->setHashtagUseCount(1);
												}else{
													$hash->setHashtagUseCount(($count + 1));
												}
												$this->doctrine->em->persist($hash);
												$this->doctrine->em->persist($post);
											}
										}
									}else{

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
									$hash1->setHashtag($hashtag);
									$hash1->setHashtagUseCount(1);
									$this->doctrine->em->persist($hash1);
									$post->addHashTag($hash1);
									$this->doctrine->em->persist($post);

								}

							}
						}


						$slug = $this->postRepository->createSlug($headline);

						$post->setSlug($slug);


						if($userType == "user"){
							$post->setPostStatus("Publish");

						}elseif($userType == "anonymous"){
							$post->setPostStatus("Anonymous");
						}

						$post->setSourceOfMedia($source);


						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						$slugCorrection = $post->getSlug()."-".$post->getId();

						$post->setSlug($slugCorrection);

						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						$data['id'] = $post->getId();
						if(null != $post->getHeadline()){
							$data['title'] = $post->getHeadline();
						}

						if(null != $post->getDescription()){
							$data['description'] = $post->getDescription();
						}

						if(null != $post->getCategory()){
							$data['category'] = $post->getCategory()->getName();
						}

						if(null != $post->getUserImpact()){
							$data['impact'] = $post->getUserImpact()->getArea();
						}

						if(null != $post->getFiles()){

							$data['files'] = array();
							foreach ($post->getFiles() as $file) {
								$tmpFile = array();
								$tmpFile['image'] = $file->getFilePath();
								$data['files'][] = $tmpFile;
							}
						}

						if(null != $post->getHashtags()){
							$data['hashtags'] = array();
							foreach($post->getHashtags() as $hashtag){
								$data['hashtags'][] = $hashtag->getHashtag();
							}
						}

						if(null != $post->getPostType()){
							$data['postType'] = $post->getPostType();
						}

						if(null != $post->getSourceOfMedia()){
						
						}

						$data['author'] = $post->getAuthor()->getUserName();

						$response->setSuccess(true);
						$response->setData(array('data'=>$data));
						$response->setError('');					

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'2002',
							'msg'=>'User does not exist'));				
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'2005',
						'msg'=>'Auth Token not set'
						));
				}
			}else{
				$response->setSuccess(false);
				$response->setData(null);
				$response->setError(array(
					'code'=>'1100',
					'msg'=>'Method Error'));	
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'code'=>'1099',
				'msg'=>'Invalid API Key'));
		}

		$response->respond();
		die();
	}
}
?>
