<?php 
class RecentController extends CI_Controller {
	var $postRepository;
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->load->helper('api');
 		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
 	}
 
 	function index(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					$posts = $this->postRepository->getRecentNewsPosts();

					if(null != $posts){
						$data = array();

						foreach ($posts as $post) {
							$temp['id'] = $post->getId();
							$temp['headline'] = $post->getHeadline();
							
							if($post->getIsAnonymous() == true){
								$temp['authorId'] = 0;
								$temp['author'] = "Anonymous";
								$temp['profilePicture'] = "";
							}else{
								$temp['authorId'] = $post->getAuthor()->getId();
								$temp['author'] = $post->getAuthor()->getUserName();
								$temp['profilePicture'] = $post->getAuthor()->getProfilePicturePath();
							}

							if(null != ($post->getUserImpact())) {
								$temp['impact'] = $post->getUserImpact()->getArea();
							}

							$temp['createdOn'] = $post->getCreatedOn()->format('d-M-Y');

							$temp['type'] = $post->getPostType();

							$temp['files'] = array();

							if("Image" == $post->getPostType()) {

								foreach ($post->getFiles() as $file) {
									$tempFile = array();
									$tempFile['id'] = $file->getId();								
									$tempFile['filePath'] = base_url($file->getDeviceImage());
									$temp['files'][] = $tempFile;
								}
							} else {
								$tempFile = array();
								$file = $post->getFiles();
								// $file = $file[0];

								foreach ($file as $f) {

									$tempFile['id'] = $f->getId();
									$tempFile['filePath'] = base_url($f->getMp4());
									$tempFile['thumb'] = base_url($f->getDeviceImage());
									$temp['files'][] = $tempFile;
								}

							}

							if(!is_null($post->getSharedCount())){
								$temp['numberOfShares'] = $post->getSharedCount();								
							}else{
								$temp['numberOfShares'] = "0";
							}

							if(!is_null($post->getPostDetails())){
								$temp['views'] = $post->getPostDetails()->getNumberOfViews();
								if(null != $post->getPostDetails()->getRating()){
									$temp['rating'] = $post->getPostDetails()->getRating();
								}else{
									$temp['rating'] = 0;
								}
							}else{
								$temp['views'] = "0";
								$temp['rating'] = 0;
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
							'code'=>'',
							'msg'=>'No Recent Posts were found'
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
 					'msg'=>'API Key is not set'
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

 	function paginate(){
		$response = new Response();
		$headers = apache_request_headers();
		$data = array();

		try {
			if($headers['api-key'] == API_KEY && isset($headers['api-key'])) {

				if($_SERVER['REQUEST_METHOD'] == "POST"){
					if(isset($_POST['postIds'])){

						$postIds = explode(',', $_POST['postIds']);
						array_pop($postIds);
						$posts = $this->postRepository->getRecentNewsPosts();

						if(null != $posts){
							
							$count = 0;
							$toSend = array();
							foreach ($posts as $post) {
								if(in_array($post->getId(), $postIds)) {
									continue;
								}
								if($count > 10) {
									break;
								}
								$toSend[] = $post;
								$count++;
							}

							foreach($toSend as $post) {
								$temp = array();
								$temp['id'] = $post->getId();
								$temp['headline'] = $post->getHeadline();
								
								if($post->getIsAnonymous() == true){
									$temp['authorId'] = 0;
									$temp['author'] = "Anonymous";
									$temp['profilePicture'] = "";
								}else{
									$temp['authorId'] = $post->getAuthor()->getId();
									$temp['author'] = $post->getAuthor()->getUserName();
									$temp['profilePicture'] = $post->getAuthor()->getProfilePicturePath();
								}
								
								// $date = checkDateTimeDiff($post->getUpdatedOn());
								$temp['createdOn'] = $post->getCreatedOn()->format('d-M-Y');

								$temp['impact'] = $post->getUserImpact()->getArea();

								if(!is_null($post->getSharedCount())){
									$temp['numberOfShares'] = $post->getSharedCount();								
								}else{
									$temp['numberOfShares'] = "0";
								}

								$temp['files'] = array();

								if("Image" == $post->getPostType()) {

									foreach ($post->getFiles() as $file) {
										$tempFile = array();
										$tempFile['id'] = $file->getId();								
										$tempFile['filePath'] = base_url($file->getDeviceImage());
										$temp['files'][] = $tempFile;
									}
								} else {
									$tempFile = array();
									$file = $post->getFiles();
									$file = $file[0];
									$tempFile['id'] = $file->getId();
									$tempFile['filePath'] = base_url($file->getMp4());
									$tempFile['thumb'] = base_url($file->getDeviceImage());
									$temp['files'][] = $tempFile;
								}

								if(!is_null($post->getPostDetails())){
									$temp['views'] = $post->getPostDetails()->getNumberOfViews();
									$temp['rating'] = $post->getPostDetails()->getRating();
								}else {
									$temp['views'] = "0";
									$temp['rating'] = 0;
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
								'code'=>'',
								'msg'=>'No Posts to display'
							));
						}
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
						'code'=>'1100',
						'msg'=>'Method Error!'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'1099',
					'msg'=>'Invalid API Key'));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}

		$response->respond();
		die();	
	}
 } ?>