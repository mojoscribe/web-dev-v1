<?php 
use Entity\Post;
use Entity\File;
use Entity\Hashtags;

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
		$this->load->file('application/classes/Response.php');
		$this->load->helper('api');
		$this->load->helper('file');
		$this->load->file('application/classes/VideoHandler.php');
		$this->load->file('application/classes/ImageHandler.php');	
	}

	function index(){
		$response = new Response();
		$headers = apache_request_headers();
		if(isset($headers['api-key'])){
			if(checkApiKey()){
				$user = checkAuthToken();
				if(false != $user){
					$drafts = $this->postRepository->getAllDrafts($user);

					if(false != $drafts){
						$data = array();
						foreach ($drafts as $draft) {

							$temp['id'] = $draft->getId();
							$temp['headline'] = $draft->getHeadline();
							// $date = checkDateTimeDiff($draft->getUpdatedOn());
							$temp['updatedOn'] = $draft->getUpdatedOn()->format('d-M-Y');

							$temp['files'] = array();
							foreach ($draft->getFiles() as $file) {
								$tmp['id'] = $file->getId();
								$tmp['file'] = $file->getFilePath();
								$temp['files'][] = $tmp;
							}

							$data[] = $temp;
						}

						$response->setSuccess(true);
						$response->setData($data);
						$response->setError('');

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'5001',
							'msg'=>'No drafts for user'
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
					'code'=>'1099',
					'msg'=>'Invalid API Key'
				));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'code'=>'1098',
				'msg'=>'API Key not set'
			));
		}

		$response->respond();
		die();
	}

	function publishDrafts(){
		$response = new Response();
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(false != checkApiKey()){
					if(isset($headers['auth-token'])){
						$user = checkAuthToken();
						if(false != $user){
							if(isset($_POST['postIds'])){

								$_POST['postIds'] = explode(',', $_POST['postIds']);
								array_pop($_POST['postIds']);

								foreach ($_POST['postIds'] as $draftId) {

									$post = $this->postRepository->getPost($draftId);

									if(false != $post && $post->getAuthor()->getId() == $user->getId()){
										$slug = $this->postRepository->createSlug($post->getHeadline());
										$post->setSlug($slug);
										
										$post->setPostStatus("PUBLISHED");

										$this->doctrine->em->persist($post);
										$this->doctrine->em->flush();

									}else{
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array(
											'code'=>'4003',
											'msg'=>'The post with given draft Id is not available'
										));

										$response->respond();
										die();
									}
								}

								$response->setSuccess(true);
								$response->setData(array('msg'=>'Drafts Published'));
								$response->setError('');

							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array(
									'code'=>'5002',
									'msg'=>'Draft Id not set'
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
							'msg'=>'Auth token not set'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'1099',
						'msg'=>'Invalid API Key'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'1098',
					'msg'=>'API Key not set'
				));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Method Error'));
		}
		$response->respond();
		die();
	}

	function deleteDraft(){
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

									foreach ($_POST['postIds'] as $postId) {
										$post = $this->postRepository->getPost($post);

										if(false != $post){
											$this->doctrine->em->remove($post);
											$this->doctrine->em->flush();
										}else{
											$response->setSuccess(false);
											$response->setData('');
											$response->setError(array(
												'code'=>7001,
												'msg'=>'Post Not found'
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
								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'',
										'msg'=>'PostIds not set'
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
								'msg'=>'User with the given Auth Token not found'
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
						'msg'=>'Invalid API Key'
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
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'code'=>'',
				'msg'=>''
			));
		}
		$response->respond();
		die();
	}

	function editDraft(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(false != checkApiKey()){
					if($_SERVER['REQUEST_METHOD'] == "POST"){
						if(isset($headers['auth-token'])){
							$user = checkAuthToken();

							if(false != $user){
								$draftId = $_POST['draftId'];

								$draft = $this->postRepository->getPost($draftId);

								if(false != $draft){
									$data['headline'] = $draft->getHeadline();
									$data['updatedOn'] = $draft->getUpdatedOn();
									$data['files'] = array();

									foreach ($draft->getFiles() as $file) {
										$data['files'][] = $file->getBig();
										break;
									}

									$data['hashtags'] = array();
									foreach ($draft->getHashtags() as $hashtag) {
										$data['hashtags'][] = $hashtag->getHashtag();							
									}

									$data['category'] = $draft->getCategory()->getName();
									$data['userImpact'] = $draft->getUserImpact()->getArea();

									if(null != $draft->getDescription()){
										$data['description'] = $draft->getDescription();
									}

									$response->setSuccess(true);
									$response->setData(array('data'=>$data));
									$response->setError('');
								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'4003',
										'msg'=>'The Post with given draftId is not available'
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
								'msg'=>'Auth token not set'
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
						'code'=>'1099',
						'msg'=>'Invalid API Key'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'1098',
					'msg'=>'API key not set'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'msg'=>'Oops! SOmething went wrong! We are looking into it!'
			));
		}
		$response->respond();
		die();
	}

	function saveEditedDraft(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])){
				
				extract($_POST);
				$apiKey = checkApiKey();
				if(false != $apiKey){
					if(!isset($_POST['id'])) {
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'DraftId is not set! Impossible to continue'));
					}else{

						$user = checkAuthToken();

						if(false != $user){
							$draftId = $_POST['id'];

							$draft = $this->postRepository->getPost($draftId);

							if(false != $draft){
								$draft->setHeadline($headline);
								if(null != $description){
									$draft->setDescription($description);
								}

								if(null != $_POST['category']){
									$category = $this->categoryRepository->getCategory($_POST['category']);
									$draft->setCategory($category);
								}

								if(null != $_POST['userImpact']){
									$userImpact = $this->impactRepository->findImpact($_POST['userImpact']);
									$draft->setUserImpact($userImpact);
								}

								if(null != $_POST['hashtags']){
									$words = explode(" ", trim($hashtags));

									$spaces=array();
									$hashtags1=array();
									foreach($words as $word){
										if($word==' '){
											array_push($spaces,$word);
										}else{
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

								$this->doctrine->em->persist($post);
								$this->doctrine->em->flush();

								$response->setSuccess(true);
								$response->setData(array('msg'=>'Draft Saved'));
								$response->setError('');

							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array('msg'=>'The Post with given draftId is not available'));
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'User does not exist'));
						}
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Invalid API Key'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Method Error or DraftId not found'));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Oops! Something went wrong! We are looking into it!'));
		}
		$response->respond();
		die();
	}


	function saveDraft(){
		$response = new Response();
		$this->load->helper('file');
		if(checkApiKey()){

			if($_SERVER['REQUEST_METHOD'] == "POST"){

				$headers = apache_request_headers();
				if(isset($headers['auth-token'])){

					$user = checkAuthToken();

					if(false != $user){
						extract($_POST);

						$post = "";
						if(isset($_POST['id']) && "" != $_POST['id']){
							$post = $this->postRepository->getPost($_POST['id']);
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

								$videoHandler = new VideoHandler($data['file_name']);
								$resized = $videoHandler->generateThumbnail();

								$userName = "";
								if($userType == "user"){
									// TODO: Watermark user name 
									$videoHandler->waterMarkImage($user->getUserName());
									$userName = $user->getUserName();
								}

								$file = new File();
								$file->setFilePath(base_url('uploads/'.$data['file_name']));

								$file->setSmall($resized['small']);
								$file->setThumb($resized['thumb']);
								$file->setLong($resized['long']);
								$file->setBig($resized['big']);
								$file->setNewsroom($resized['newsroom']);
								$file->setDeviceImage($resized['device']);

								/*$file->setPost($post);
								$this->doctrine->em->persist($file);
								$post->addFiles($file);
								$this->doctrine->em->persist($post);*/

								$file->setPost($post);
								$this->doctrine->em->persist($file);
								$post->addFiles($file);
								$this->doctrine->em->persist($post);
								$this->doctrine->em->flush();

							}
						}


						$post->setAuthor($user);

						if(isset($_POST['position'])){
							$coOrds = array();

							$coOrds = explode(',', $_POST['position']);

							$reverse = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coOrds[0].",".$coOrds[1]);
							$reverse = json_decode($reverse,true);

							$data = array();

							$data = $reverse['results'][0]['address_components'];

							$locality = "";
							$administrative = "";

							foreach ($data as $d) {

								if($d['types'][0] == "locality" && $d['types'][1] == "political"){
									$locality = $d['long_name'];
								}

								if($d['types'][0] == "administrative_area_level_2" && $d['types'][1] == "political"){
									$administrative = $d['long_name'];
								}
							}

							if($post->getLocation() == ","){
								$post->setLocation(NULL);
							}else{
								$post->setLocation($locality.",".$administrative);
							}

							$post->setLatitude($coOrds[1]);

							$post->setLongitude($coOrds[0]);
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

									$post->getHashTags()->clear();

									$this->doctrine->em->persist($post);
									$this->doctrine->em->flush();

									$post->addHashTag($hash);
									$count = $hash->getHashTagUseCount();
									
									if(null == $count){
										$hash->setHashtagUseCount(1);
										
									}else{
										
										$hash->setHashtagUseCount(($count + 1));
									}
									$this->doctrine->em->persist($hash);
									$this->doctrine->em->persist($post);

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

						$post->setPostStatus("DRAFT");

						if($userType == "user"){
							$post->setIsAnonymous(false);
						}elseif($userType == "anonymous"){
							$post->setIsAnonymous(true);
						}

						$post->setSourceOfMedia($source);

						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						$slugCorrection = $post->getSlug()."-".$post->getId();

						$post->setSlug($slugCorrection);

						$this->doctrine->em->persist($post);
						$this->doctrine->em->flush();

						$postData['id'] = $post->getId();

						if(null != $post->getHeadline()){
							$postData['title'] = $post->getHeadline();
						}

						$response->setSuccess(true);
						$response->setData($postData);
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