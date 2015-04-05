<?php 
class SearchController extends CI_Controller {
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
	 			if(false != checkApiKey()){
	 				if($_SERVER['REQUEST_METHOD'] == "POST"){
	 					if(isset($_POST['q'])){

							$hashtag = $this->doctrine->em->getRepository('Entity\Hashtags')->findOneBy(array('hashtag'=>$_POST['q']));

							$searchResults = null;

							if(null == $hashtag){
								$searchResults = $this->postRepository->getSearchResults($_POST['q']);				
							}else{

								$searchResults = $this->postRepository->getSearchResultsByHashtags($_POST['q'],$hashtag);
							}

	 						if(false != $searchResults){
	 							$data = array();
	 							foreach ($searchResults as $searchResult) {
									$temp['id'] = $searchResult->getId();
									$temp['headline'] = $searchResult->getHeadline();
									$temp['description'] = $searchResult->getDescription();
									$date = $searchResult->getUpdatedOn();
									$date = $date->format('d-M-Y');
									$temp['createdOn'] = $date;

									$temp['impact'] = $searchResult->getUserImpact()->getArea();

									if(null != $searchResult->getSharedCount()){
										$temp['numberOfShares'] = $searchResult->getSharedCount();
									}else{
										$temp['numberOfShares'] = "0";
									}

									if($searchResult->getPostStatus() == "Anonymous"){
										$temp['authorId'] = "";
										$temp['author'] = "Anonymous";
										$temp['profilePicture'] = "";
									}else{
										$temp['authorId'] = $searchResult->getAuthor()->getId();
										$temp['author'] = $searchResult->getAuthor()->getUserName();
										$temp['profilePicture'] = $searchResult->getAuthor()->getProfilePicturePath();
									}

									if(!is_null($searchResult->getPostDetails())){
										$temp['views'] = $searchResult->getPostDetails()->getNumberOfViews();
										if(null != $searchResult->getPostDetails()->getRating()){
											$temp['rating'] = $searchResult->getPostDetails()->getRating();
										}else{
											$temp['rating'] = 0;
										}
									}else{
										$temp['views'] = "0";
										$temp['rating'] = 0;
									}
									
									$temp['hashtags'] = array();

									foreach ($searchResult->getHashtags() as $hashtag) {
										$tmpHash = array();
										$tmpHash['id'] = $hashtag->getId();
										$tmpHash['hashtag'] = $hashtag->getHashtag();
										$temp['hashtags'][] = $tmpHash;
									}

									$temp['type'] = $searchResult->getPostType();

									$temp['files'] = array();

									if("Image" == $searchResult->getPostType()) {

										foreach ($searchResult->getFiles() as $file) {
											$tempFile = array();
											$tempFile['id'] = $file->getId();								
											$tempFile['filePath'] = base_url($file->getBig());
											$temp['files'][] = $tempFile;
										}
									} else {
										$tempFile = array();
										$file = $searchResult->getFiles();
										$file = $file[0];
										$tempFile['id'] = $file->getId();
										$tempFile['filePath'] = base_url($file->getMp4());
										$tempFile['thumb'] = base_url($file->getBig());
										$temp['files'][] = $tempFile;
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
	 								'msg'=>'No search results were found'
	 								));
	 						}
	 					}else{
	 						$response->setSuccess(false);
	 						$response->setData('');
	 						$response->setError(array(
	 							'code'=>5001,
	 							'msg'=>'Search query has not been set'
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
 			$response->setError(array(
 				'code'=>'',
 				'msg'=>'Oops! Something went wrong!'
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

						$hashtag = $this->doctrine->em->getRepository('Entity\Hashtags')->findOneBy(array('hashtag'=>$_POST['q']));

						$posts = null;

						if(null == $hashtag){
							$posts = $this->postRepository->getSearchResults($_POST['q']);				
						}else{

							$posts = $this->postRepository->getSearchResultsByHashtags($_POST['q'],$hashtag);
						}

						$postIds = explode(',', $_POST['postIds']);
						array_pop($postIds);

						// $posts = $this->postRepository->getAllRecords();
						$data = array();

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
									$temp['numberOfShares'] = 0;
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
									$temp['views'] = 0;
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