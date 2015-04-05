<?php 
class CategoryController extends CI_Controller {
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
 					
					if($_SERVER['REQUEST_METHOD'] == "POST"){
						$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();

						$data = array();
						foreach ($categories as $category) {
							$temp['id'] = $category->getId();
							$temp['name'] = $category->getName();

							$data[] = $temp;
						}

						$response->setSuccess(true);
						$response->setData($data);
						$response->setError('');
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


 	function getPostsForCategory(){
 		$response = new Response();
 		try {

 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
					if($_SERVER['REQUEST_METHOD'] == "POST"){
						$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id'=>$_POST['categId']));

						if(!is_null($category)){
							$posts = $this->postRepository->getPostsByCategory($category);
			 				$noPosts = true;
							$data = array();

							if(false != $posts){
								
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
									
									if(!is_null($post->getSharedCount())){
										$temp['numberOfShares'] = $post->getSharedCount();								
									}else{
										$temp['numberOfShares'] = "0";
									}

									$temp['impact'] = $post->getUserImpact()->getArea();

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
										$file = $file[0];
										$tempFile['id'] = $file->getId();
										$tempFile['filePath'] = base_url($file->getMp4());
										$tempFile['thumb'] = base_url($file->getDeviceImage());
										$temp['files'][] = $tempFile;
									}

									if(!is_null($post->getPostDetails())){
										$temp['views'] = $post->getPostDetails()->getNumberOfViews();
										$temp['rating'] = $post->getPostDetails()->getRating();
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
									'msg'=>'No posts are available for the category'
									));
							}
						
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'',
								'msg'=>'Category not found'
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

 	function paginate(){
		$response = new Response();
		$headers = apache_request_headers();
		$data = array();
		try {
			if($headers['api-key'] == API_KEY && isset($headers['api-key'])) {

				if($_SERVER['REQUEST_METHOD'] == "POST"){
					if(isset($_POST['postIds'])){
						$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id'=>$_POST['categId']));

						if(!is_null($category)){
							$posts = $this->postRepository->getPostsByCategory($category);
							$data = array();
							if(null != $posts){
								$postIds = explode(',', $_POST['postIds']);
								array_pop($postIds);

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
									
									if($post->getPostStatus() == "Anonymous"){
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

									$temp['type'] = $post->getPostType();

									$temp['files'] = array();

									foreach ($post->getFiles() as $file) {
										$tempFile = array();
										$tempFile['id'] = $file->getId();
										$tempFile['filePath'] = $file->getDeviceImage();
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
								'msg'=>'Category not found'
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