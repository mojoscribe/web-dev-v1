<?php
use Entity\Hashtags;
use Entity\Post;
use Entity\File;
use Entity\ToProcess;
use Entity\UserNotifications;
class DraftsController extends CI_Controller {
	var $userRepository;
	var $postRepository;
	var $impactRepository;
	var $categoryRepository;
	var $hashtagRepository;
	var $post;
	function __construct(){
		parent::__construct();
		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->impactRepository = $this->doctrine->em->getRepository('Entity\Impact');
		$this->categoryRepository = $this->doctrine->em->getRepository('Entity\Category');
		$this->hashtagRepository = $this->doctrine->em->getRepository('Entity\Hashtags');
		$this->load->helper('file_helper');
		$this->load->file('application/classes/Response.php');
		$this->load->file('application/classes/ImageHandler.php');
		$this->load->file('application/classes/VideoHandler.php');
	}

	function index(){
		$this->load->library('user_agent');

		$cookie = null;
			if(null != $_COOKIE && isset($_COOKIE['click'])){
			$cookie = $_COOKIE['click'];
		}

		if($this->agent->is_mobile() && !isset($cookie)){
			$this->load->view('mobile/android.html');
		}else{
			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();

				$email = $user->getEmail();
				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$profile));
				// $this->load->view('user/header');
				$this->load->view('user/sidebar');
				$this->load->view('user/drafts');
				$this->load->view('footer',array('scripts'=>array('controllers/draftscontroller.js')));
			}else{
				redirect('login/logout');
			}
		}
	}

	function getAllDrafts(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if(isUserLoggedIn()){
					$id = $_SESSION['id'];
					$email = $_SESSION['email'];

					$user = $this->userRepository->checkUser($email);

					$drafts = $this->postRepository->getAllDrafts($user);

					if($drafts){
						$data = array();
						foreach ($drafts as $draft) {
							$temp['id'] = $draft->getId();
							$temp['title'] = $draft->getHeadline();
							$date = $draft->getUpdatedOn();
							$date = $date->format('d-M-Y');
							$temp['updatedOn'] = $date;
							$hashtags = $draft->getHashTags();
							
							$temp['hashtags'] = array();

							foreach ($hashtags as $hashtag) {
								$hash['name'] = $hashtag->getHashtag();
								$temp['hashtags'][] = $hash;
							}

							$temp['files'] = array();
							foreach ($draft->getFiles() as $file) {
								$temp['files'][] = $file->getFilePath();
								break;
							}
							$temp['isAnonymous'] = false;
							if($draft->getIsAnonymous() == true){
								$temp['isAnonymous'] = true;
							}else{
								$temp['isAnonymous'] = false;
							}

							$data[] = $temp;
						}
						
						$response->setSuccess(true);
						$response->setData($data);
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setError(array('msg'=>'Error fetching drafts'));
					}
				}else{
					$response->setSuccess(false);
					$response->setError(array('msg'=>'User Not Logged in'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Oops! Something went wrong!'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Dude! Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function publishSingleDraft(){
		if($_SERVER['REQUEST_METHOD'] == "GET"){
			$id = $_GET['id'];

			$post = $this->postRepository->getPost($id);

			if(null != $post){

				$slug = $this->postRepository->createSlug($post->getHeadline());
				$post->setSlug($slug);

				if($post->getPostType() == "Video"){
					$post->setPostStatus("PROCESSING");

					foreach ($post->getFiles() as $videos) {

						$toProcess = new ToProcess();
						$toProcess->setFile($videos);
						$toProcess->setUserName($post->getAuthor()->getUserName());
						$toProcess->setVidPath($videos->getFilePath());

						$this->doctrine->em->persist($toProcess);

						//Add notification for user - "Your video is being processed"
						$notif = new UserNotifications();
						$notif->setNotifyText("Your video post is being processed and will be published soon");
						$notif->setLink("#");
						$notif->setUser($user);
						$notif->setImage(base_url($videos->getThumb()));
						$notif->setActionType('POST');
						$notif->setActionId($post->getId());

						$this->doctrine->em->persist($notif);
						$this->doctrine->em->flush();
					}

					$this->doctrine->em->flush();
				}else{
					$post->setPostStatus("PUBLISHED");
				}
				
				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				redirect('drafts?published');
			}else{
				redirect('drafts?errorPublishing');
			}
		}else{
			redirect('drafts');
		}
	}

	function publishDraft(){
		
		if($_SERVER['REQUEST_METHOD'] == "GET"){
			$response = new Response();
			if(false != checkCsrf()){
				try {
					$id = json_decode($_GET['id']);

					$post = $this->postRepository->getPost($id);

					if(null != $post){
						$post->setPostStatus("PUBLISHED");
						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						// $this->getAllDrafts();


						$response->setSuccess(true);
						$response->setData(array('msg'=>'published'));
						$response->setError('');
							// redirect('post?published');
					}else{
						$response->setSuccess(false);
						$response->setError(array('msg'=>'post not found'));
					}
				} catch (Exception $e) {
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Oops! Something went wrong!'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Dude! Cross domain requests are not allowed'));
			}

			$response->respond();
			die();
		}else{
			redirect('drafts');
		}
	}

	function deleteDrafts(){
		
		if($_SERVER['REQUEST_METHOD'] == "GET"){
			$response = new Response();
			if(false != checkCsrf()){
				try {
					$id = json_decode($_GET['id']);
					
					$post = $this->postRepository->getPost($id);
					if(null != $post){

						if(null != $post->getFiles()){
							foreach ($post->getFiles() as $file) {
								$file = explode("http://localhost/mojo-scribe", trim($file->getFilePath()));
							}
						}

						$this->doctrine->em->remove($post);
						$this->doctrine->em->flush();

						// $this->getAllDrafts();

						$response->setSuccess(true);
						$response->setData(array('msg'=>'deleted'));
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setError(array('msg'=>'post not found'));
					}
				} catch (Exception $e) {
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Something went wrong'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Dude! Cross domain requests are not allowed'));
			}
		$response->respond();
		die();

		}else{
			redirect('drafts');
		}
	}

	function editDraftView(){
		try {

			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();

				$email = $user->getEmail();
				$data['userName'] = $user->getUserName();
				$data['picture'] = $user->getProfilePicturePath();

				$user = $this->userRepository->checkUser($email);

				if($_SERVER['REQUEST_METHOD'] == "GET"){
					$id = $_GET['id'];

					$post = $this->postRepository->getPost($id);

					if($post){
						$draftId = $post->getId();

						$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();

						$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();

						$this->load->view('header',array('data'=>$data));
						$this->load->view('user/sidebar');
						$this->load->view('user/editDraft',array('draftId'=>$draftId,'categories'=>$categories,'impacts'=>$impacts));
						$this->load->view('footer',array('scripts'=>array('controllers/postcontroller.js','slider.js', 'bxslider/jquery.bxslider.min.js') ,'styles' => array('js/bxslider/jquery.bxslider.css')));

					}else{

					}
				}else{
					redirect('drafts');
				}
			}else{
				redirect('/?sessionexpired');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function saveEditedDraft(){

		session_start();
		if(isset($_SESSION['id'])){
			$id = $_SESSION['id'];
			$email = $_SESSION['email'];

			$user = $this->userRepository->checkUser($email);

			if($_SERVER['REQUEST_METHOD'] == "POST"){

				try {
					extract($_POST);

					$post = $this->postRepository->getPost($_POST['id']);
					if($post){

						if(isset($_POST['saveToDraftsSubmit'])){
							$post->setPostStatus("Draft");
							$draft = true;
						}elseif(isset($_POST['postSubmit'])){
							$post->setPostStatus("PUBLISHED");
						}


						if(!empty($_FILES)){

							if($_POST['postType'] == "Image"){
								if(!empty($_FILES['images']['name'][0])){
								
									$file_ary = reArrayFiles($_FILES['images']);

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
											redirect('editDraftView?id='.$_POST['id'].'&error='.$this->upload->display_errors());
										} else {
											$data = $this->upload->data();
											$file = new File();
											$file->setFilePath(base_url('uploads/'.$data['file_name']));
											$file->setPost($post);
											$this->doctrine->em->persist($file);
											$post->addFiles($file);
											$this->doctrine->em->persist($post);
										}
									}
								}
							}elseif($_POST['postType'] == "Video" && null != $_FILES['video']['name']){
								
								$config ['upload_path'] = './uploads/';
								$config ['allowed_types'] = 'avi|mp4|3gp|mpg';
								$config ['max_size'] = '30000'; //30MB  /*What is the limit?*/

								$this->load->library ( 'upload', $config );

								if (!$this->upload->do_upload('video')) {
									redirect('upload?error='.$this->upload->display_errors());
								} else {


									$data = $this->upload->data();
									$file = new File();
									$file->setFilePath(base_url('uploads/'.$data['file_name']));
									$file->setPost($post);
									$this->doctrine->em->persist($file);
									$post->addFiles($file);
									$this->doctrine->em->persist($post);
								}
							}
						}

						if($title != null){
							$post->setHeadline($title);
						}else{
							$post->setHeadline('');
						}
						
						if($description != null){
							$post->setDescription($description);
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


						$post->setAuthor($user);

						$slug = $this->postRepository->createSlug($title);

						if(false != $slug){
							$post->setSlug($slug);
						}

						if($_POST['hashtags']){
							$words = explode(" ", trim($hashtags));

							$spaces=array();
							$hashtags1=array();
							foreach($words as $word)
							{
								if($word==' ')
								{
									array_push($spaces,$word);
								}
								else
								{
									array_push($hashtags1,$word);
								}
							}

							foreach ($hashtags1 as $hashtag) {
								$hash = $this->hashtagRepository->checkHashtag($hashtag);

								if($hash){
									$hT = $post->getHashTags();
									$hashTs = array();
									foreach ($hT as $ht) {
										$temp = $ht->getHashtag();
										$hashTs[] = $temp;
									}
									$exists = in_array($hash->getHashtag(), $hashTs);
									if($exists != 1){
										$post->addHashTag($hash);
										$this->doctrine->em->persist($post);
									}
								}else{
									$hash1 = new Hashtags();

									$hash1->setHashtag($hashtag);
									$this->doctrine->em->persist($hash1);
									$post->addHashTag($hash1);
									$this->doctrine->em->persist($post);
										
								}
							}

						}
						
						$this->doctrine->em->flush();

						if ($draft == true) {
							redirect('drafts?edited');
						} else {
		 					redirect('drafts?uploaded');
						}
					}else{
						redirect('drafts?error=postnotexist');
					}
				} catch (Exception $e) {
					echo "<pre>";
					print_r($e->getMessage());
					die();
					// redirect('');
				}
			}else{

			}
		}else{
			redirect('login?sessionexpired');
		}
	}


	function autoSaveDraft(){

		$response = new Response();
		if(false != checkCsrf()){
			try {
				if(isUserLoggedIn()){
					$id = $_SESSION['id'];
					$email = $_SESSION['email'];

					$user = $this->userRepository->checkUser($email);

					if($_SERVER['REQUEST_METHOD'] == "POST"){

						$_POST = json_decode($_POST['post']);
						$_POST = get_object_vars($_POST);

						try {

 							extract($_POST);

							if(isset($_POST['id']) && null != $_POST['id']){
								$post = $this->postRepository->getPost($_POST['id']);
							}else{
								$post = new Post();
							}

							$post->setPostStatus("DRAFT");	

							if($_POST['type'] == "user"){
								$post->setIsAnonymous(false);
							}elseif($_POST['type'] == "anonymous"){
								$post->setIsAnonymous(true);
							}
							$draft = true;

							if($_FILES != null){

								if($_POST['postType'] == "Image"){

									$file_ary = draftArrayFiles($_FILES['files']);
									
									foreach ($file_ary as $image) {

										$_FILES['images']['name'] = $image['name'];
							            $_FILES['images']['type'] = $image['type'];
							            $_FILES['images']['tmp_name'] = $image['tmp_name'];
							            $_FILES['images']['error'] = $image['error'];
							            $_FILES['images']['size'] = $image['size'];
										
										$config ['upload_path'] = './uploads/';
										$config ['allowed_types'] = 'jpeg|png|jpg';
										$config ['max_size'] = '30000'; //30MB  /*What is the limit?*/
											
										$this->load->library ( 'upload', $config );
													
										if (!$this->upload->do_upload('images')) {
											$response->setSuccess(false);
											$response->setData('');
											$response->setError(array('msg'=>$this->upload->display_errors()));
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
								}elseif($_POST['postType'] == "Video"){

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
											$videoHandler = new VideoHandler($data['file_name']);
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

											$file = new File();
											$file->setFilePath('uploads/'.$data['file_name']);
											
											$file->setSmall($resized['small']);
											$file->setThumb($resized['thumb']);
											$file->setLong($resized['long']);
											$file->setBig($resized['big']);
											$file->setNewsroom($resized['newsroom']);

											$file->setPost($post);
											$this->doctrine->em->persist($file);
											$post->addFiles($file);
											$this->doctrine->em->persist($post);
											$this->doctrine->em->flush();
										}
									}
								}
							}

							if(isset($_POST['location']) && null != $_POST['location']){
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

									// if($d['types'][0] == "locality" && $d['types'][1] == "political"){
									// 	$locality = $d['long_name'];
									// }

									// if($d['types'][0] == "administrative_area_level_2" && $d['types'][1] == "political"){
									// 	$administrative = $d['long_name'];
									// }

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

								// if($post->getLocation() == ","){
								// 	$post->setLocation(NULL);
								// }else{
								// 	$post->setLocation($locality.",".$administrative);
								// }

								if($post->getLocation() == ","){
									$post->setLocation(NULL);
								}else{
									$post->setLocation($formatted_address);
								}

								$post->setLatitude($coOrds[1]);

								$post->setLongitude($coOrds[0]);

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
							$post->setPostType($postType);

							$post->setAuthor($user);

							if(isset($_POST['source'])){
								$post->setSourceOfMedia(strip_tags($_POST['source']));
							}

							// echo "<pre>";
							// print_r($_POST['hashtags']);
							// die();
							$_POST['hashtags'] = json_decode($_POST['hashtags']);
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

							$this->doctrine->em->persist($post);

							$this->doctrine->em->flush();
							$data = array();

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

							$data['date'] = $post->getUpdatedOn()->format('d-M-Y');

							if(null != $post->getFiles()){

								$data['files'] = array();
								foreach ($post->getFiles() as $file) {
									$tmpFile = array();
									$tmpFile['image'] = $file->getBig();
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

							if($post->getIsAnonymous() == true){
								$data['author'] = "Anonymous";
							}else{
								$data['author'] = $post->getAuthor()->getUserName();
							}

							if($_POST['linkClick'] == "draft"){
								$response->setSuccess(true);
								$response->setData(array('data'=>$data,'target'=>"draft"));
								$response->setError('');
							}elseif($_POST['linkClick'] == "previewPost"){
								$response->setSuccess(true);
								$response->setData(array('data'=>$data,'target'=>"preview"));
								$response->setError('');
							}
							
						} catch (Exception $e) {
							$response->setSuccess(false);
							$response->setError(array('msg'=>$e->getMessage()));
							// redirect('');
						}

						if(isset($_GET['sse'])) {

							$resp = array(
								'success' => $response->getSuccess(),
								'error' => $response->getError(),
								'data' => $response->getData(),
							);
							
							header('Content-Type: text/event-stream');
							header('Cache-Control: no-cache');
							echo "data: " . json_encode($resp) . "\n\n";
							flush();
						
						}  else {
								
						}
					}else{

					}
					$response->respond();
					die();	
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('
						msg'=>'sessionexpired'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Dude! Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}
}