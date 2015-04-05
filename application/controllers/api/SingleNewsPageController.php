<?php 
use Entity\Post;
use Entity\RatingLog;
use Entity\PostDetails;
class SingleNewsPageController extends CI_Controller {
	var $postRepository;
	var $ratingLogRepository;
	function __construct(){
		parent::__construct();
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->ratingLogRepository = $this->doctrine->em->getRepository('Entity\RatingLog');
		$this->load->file('application/classes/Response.php');
		$this->load->helper('api');
	}

	function index(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if($headers['api-key'] == API_KEY && isset($headers['api-key'])){
				if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['postId']) && isset($_POST['isViewed'])){
					$postId = $_POST['postId'];

					$post = $this->postRepository->getPost($postId);
					
					if(null != $post && $post->getPostStatus() == "PUBLISHED"){
						$data = array();
						
						$data['id'] = $post->getId();
						$data['headline'] = $post->getHeadline();
						
						if($post->getIsAnonymous() == true) {
							$data['author'] = "Anonymous";
							$data['profilePicture'] = '';
							$data['authorId'] = 0;
						}else{
							$data['author'] = $post->getAuthor()->getUserName();
							$data['profilePicture'] = $post->getAuthor()->getProfilePicturePath();
							$data['authorId'] = $post->getAuthor()->getId();
						}

						$data['link'] = base_url()."single/".$post->getSlug();

						$data['date'] = $post->getUpdatedOn()->format("d-M-Y");
						$data['description'] = $post->getDescription();
						$data['impact'] = $post->getUserImpact()->getArea();
						$data['files'] = array();

						$data['type'] = $post->getPostType();

						$data['location'] = $post->getLocation();

						$data['files'] = array();

						if("Image" == $post->getPostType()) {

							foreach ($post->getFiles() as $file) {
								$tempFile = array();
								$tempFile['id'] = $file->getId();								
								$tempFile['file'] = base_url($file->getDeviceImage());
								$data['files'][] = $tempFile;
							}

						} else {

							$tempFile = array();
							$file = $post->getFiles();
							$file = $file[0];
							$tempFile['id'] = $file->getId();								
							$tempFile['file'] = base_url($file->getMp4());
							$tempFile['thumb'] = base_url($file->getDeviceImage());
							$data['files'][] = $tempFile;

						}


						/*foreach ($post->getFiles() as $file) {
							$fileData = array();
							$fileData['id'] = $file->getId();
							$fileData['file'] = $file->getFilePath();
							$data['files'][] = $fileData;
						}*/

						$data['hashtags'] = array();
						foreach ($post->getHashtags() as $hashtag) {
							$hashtagData = array();
							$hashtagData['id'] = $hashtag->getId();
							$hashtagData['hashtag'] = $hashtag->getHashtag();
							$data['hashtags'][] = $hashtagData;
						}


						$data['postType'] = $post->getPostType();

						$data['date'] = $post->getUpdatedOn()->format('d-M-Y H:i:s');

						$data['date'] = strtotime($data['date']);

						// $postDetails = $this->postDetailsRepository->findOneBy(array('post'=>$post));
						$postDetails = $post->getPostDetails();
						
						if($postDetails){
							// echo "<pre>";
							// print_r("jsdnsd");
							// die();

							// $ratingValue = $postDetails->getRating();

							
							// $data['ratingValue'] = $ratingValue;

							// if($postDetails->getNumberOfRatingUsers() == null){
							// 	$data['numberOfRates'] = 0;
								
							// }else{
							// 	$data['numberOfRates'] = $postDetails->getNumberOfRatingUsers();
								
							// }
							if($_POST['isViewed'] == false){
								
								$postDetails->setNumberOfViews($postDetails->getNumberOfViews() + 1);
								$this->doctrine->em->persist($postDetails);
								$this->doctrine->em->flush();
							}

							if($postDetails->getNumberOfViews() == null){
								$data['views'] = 0;
							}else{
								$data['views'] = $postDetails->getNumberOfViews();
							}

							if(!is_null($postDetails->getPositive_sum()) || !is_null($postDetails->getNegative_sum()) || 0 != $postDetails->getPositive_sum() || 0 != $postDetails->getNegative_sum()){
								$positive_sum = $postDetails->getPositive_sum();
								$negative_sum = $postDetails->getNegative_sum();

								$exp1 = ($positive_sum + 1.9208)/($positive_sum + $negative_sum);
								// $exp21 = ($positive_sum + $negative_sum);
								$exp21 = ($positive_sum * $negative_sum)/($positive_sum + $negative_sum);
								$exp21 = $exp21 + 0.9604;
								$exp2 = (1.96 * sqrt($exp21))/($positive_sum + $negative_sum);

								$exp3 = 1 + 3.8416/($positive_sum + $negative_sum);

								$exp = ($exp1 - $exp2)/$exp3;

								$averageRating = $exp * 1;

								$data['averageRating'] = $averageRating;
							
							}else{
								$data['averageRating'] = 0;
							}
							
							

							// $averageRating = round($averageRating/$data['numberOfRates'],4);
							

						}else{
							$postDetails = new PostDetails();

							if($_POST['isViewed'] == false){

								$postDetails->setNumberOfViews(1);
								$this->doctrine->em->persist($postDetails);
								$post->setPostDetails($postDetails);
								$this->doctrine->em->flush();
							}
							$data['views'] = 1;
							$data['averageRating'] = 0;
						}

						$data['numberOfShares'] = $post->getSharedCount();

						$response->setSuccess(true);
						$response->setData(array($data));
						$response->setError('');

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'',
							'msg'=>"Post does not exist Or is not published"
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>1100,
						'msg'=>"Method error or No postId"));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>1099,
					'msg'=>'Invalid Api Key'
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

	function saveRating(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(checkApiKey()){
					if(isset($headers['auth-token'])){
						if(false != checkAuthToken()){
							if(isset($_POST['postId']) && isset($_POST['rating'])){

								$user = checkAuthToken();
								$post = $this->postRepository->getPost($_POST['postId']);

								if(false != $post){
									if($user->getId() != $post->getAuthor()->getId() || ($post->getIsAnonymous() == true)){
										$isRated = $this->ratingLogRepository->checkLogForUser($user,$post);

										if($isRated){

											if(null != $post->getPostDetails()){
												$postDetails = $post->getPostDetails();

												if(!is_null($postDetails)){
													$actualRating = 0;

													$rating = $_POST['rating'];

													// echo "<pre>";
													// print_r($rating);
													// die();

													if($rating == -3){
														$actualRating = 3 * 35;
													}elseif ($rating == -1) {
														$actualRating = 1 * 93;
													}elseif($rating == 0.5){
														$actualRating = 0.5 * 145;
													}elseif ($rating == 1.5) {
														$actualRating = 1.5 * 153;
													}elseif ($rating == 3) {
														$actualRating = 3 * 125;
													}

													if($rating < 0){
														
														if($postDetails->getNegative_sum() == (null || 0)){
															$postDetails->setNegative_sum($actualRating);
														}else{
															$postDetails->setNegative_sum($postDetails->getNegative_sum() + $actualRating);

														}


													}elseif ($rating > 0) {
													
														if($postDetails->getPositive_sum() == (null || 0)){
															$postDetails->setPositive_sum($actualRating);
														}else{
															$postDetails->setPositive_sum($postDetails->getPositive_sum() + $actualRating);

														}
														
													}elseif ($rating = 0) {
														$response->setSuccess(false);
														$response->setData('');
														$response->setError(array('msg'=>'Post rating error'));
													
														$response->respond();
														die();
													}



													// if($postDetails->getRating() == null){
													// 	$totalRating = $rating;
													// 	$postDetails->setRating($totalRating);
													// }else{
														
													// 	$totalRating = $postDetails->getRating() + $rating;
													// 	$postDetails->setRating($totalRating);
													// }

													if($postDetails->getNumberOfRatingUsers() == null){
														$postDetails->setNumberOfRatingUsers(1);
													}else{
														$postDetails->setNumberOfRatingUsers($postDetails->getNumberOfRatingUsers() + 1);
													}


													$ratingLog = new RatingLog();
													$ratingLog->setUser($user);
													$ratingLog->setPost($post);
													$ratingLog->setRating($_POST['rating']);

													$this->doctrine->em->persist($ratingLog);

													$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();

													$data = array();

													foreach ($impacts as $impact) {
														$temp['id'] = $impact->getId();
														$temp['area'] = $impact->getArea();

														$data[] = $temp;
													}

													$positive_sum = $postDetails->getPositive_sum();
													$negative_sum = $postDetails->getNegative_sum();

													$exp1 = ($positive_sum + 1.9208)/($positive_sum + $negative_sum);
													// $exp21 = ($positive_sum + $negative_sum);

													$exp21 = ($positive_sum * $negative_sum)/($positive_sum + $negative_sum);
													$exp21 = $exp21 + 0.9604;
													$exp2 = (1.96 * sqrt($exp21))/($positive_sum + $negative_sum);

													$exp3 = 1 + 3.8416/($positive_sum + $negative_sum);

													$exp = ($exp1 - $exp2)/$exp3;

													$averageRating = $exp * 1;

													$this->load->helper('date');

													$date = new DateTime();
													$date = $date->getTimestamp();

													$createdDate = $post->getCreatedOn();
													$createdDate = $createdDate->getTimestamp();

													$interval = $date-$createdDate;

													$articleLife = $interval/3600;

													$postRank = $averageRating * log((1 + $postDetails->getNumberOfViews())/pow(2, ($articleLife/24)));

													$post->setPostRanking($postRank);

													$postDetails->setRating($averageRating);

													$this->doctrine->em->persist($postDetails);

													$this->doctrine->em->persist($post);
													$this->doctrine->em->flush();

													$response->setSuccess(true);
													$response->setData(array(
														'code'=>7501,
														'impacts'=>$data,
														'rating'=>$_POST['rating'],
														'msg'=>'Rated'
													));
													$response->setError('');
													
												}else{
													$response->setSuccess(false);
													$response->setData('');
													$response->setError(array(
														'code'=>'7004',
														'msg'=>'post details error'
													));
												}
											}else{
												$response->setSuccess(false);
												$response->setData('');
												$response->setError(array(
													'code'=>'7004',
													'msg'=>'Post details not found'
												));
											}
										}else{
											$response->setSuccess(false);
											$response->setData('');
											$response->setError(array(
												'code'=>'7002',
												'msg'=>'User has already rated the post'
											));
										}
									}else{
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array(
											'code'=>'7003',
											'msg'=>'rating own post is not allowed'
										));
									}
								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'7001',
										'msg'=>'Post not found'
									));
								}
							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array(
									'code'=>'',
									'msg'=>'postId and rating not set'
								));
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'7005',
								'msg'=>'Auth token not matching to any user'
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
				} else {
					$response->setSuccess(false);
					$response->setData("");
					$response->setError(array(
							'code' => 1001,
							'msg' => "Invalid API KEY",
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
				'code'=>'',
				'msg'=>$e->getMessage()
			));
		}

		$response->respond();
		die();
	}

	function saveImpact(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(false != checkApiKey()){
					if(isset($headers['auth-token'])){
						if(false != checkAuthToken()){
							if($_SERVER['REQUEST_METHOD'] == "POST"){
								$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST['postId']));

								if(!is_null($post)){
									$impact = $this->doctrine->em->getRepository('Entity\Impact')->findOneBy(array('id'=>$_POST['impactId']));

									$data = array();

									if(!is_null($impact)){
										$ratingLog = $this->doctrine->em->getRepository('Entity\RatingLog')->findOneBy(array('post'=>$post));
										
										$ratingLog->setUserImpact($impact);
										
										$this->doctrine->em->persist($ratingLog);
										$this->doctrine->em->flush();

										$response->setSuccess(true);
										$response->setData(array('msg'=>'Impact has been recorded'));
										$response->setError('');

									}else{
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array(
											'code'=>'',
											'msg'=>'Impact not found'
										));
									}
								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'',
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
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>2002,
								'msg'=>'Auth token incorrect'
								));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>2005,
							'msg'=>'Auth token not set'
							));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>1098,
						'msg'=>'Invalid API Key'
						));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>1099,
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

	function share(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(checkApiKey()){

					if($_SERVER['REQUEST_METHOD'] == "POST"){
						$post = $this->postRepository->getPost($_POST['id']);

						if(false != $post){
							if($post->getSharedCount() == null){
								$post->setSharedCount(1);
							}else{
								$post->setSharedCount($post->getSharedCount() + 1);
							}

							$this->doctrine->em->persist($post);
							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array(
								'msg'=>'Share count increased'
							));
							$response->setError('');
							
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'',
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
					
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>1098,
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
				'msg'=>$e->getMessage()
			));
		}
		$response->respond();
		die();
	}
}
?>