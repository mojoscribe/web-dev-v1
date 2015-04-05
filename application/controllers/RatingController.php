<?php
use Entity\Post;
use Entity\PostDetails;
use Entity\RatingLog;
class RatingController extends CI_Controller {
	var $userRepository;
	var $postRepository;
	var $ratingLogRepository;
	var $postDetailsRepository;
	function __construct() {
		parent::__construct();
		$this -> userRepository = $this -> doctrine -> em -> getRepository('Entity\User');
		$this -> postRepository = $this -> doctrine -> em -> getRepository('Entity\Post');
		$this -> ratingLogRepository = $this -> doctrine -> em -> getRepository('Entity\RatingLog');
		$this->postDetailsRepository = $this->doctrine->em->getRepository('Entity\PostDetails');
		$this -> load -> file('application/classes/Response.php');
	}

	function saveRating() {
		$response = new Response();
		if(false != checkCsrf()){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				try {

					$_POST = file_get_contents("php://input");
					
					$_POST = json_decode($_POST);
					$_POST = get_object_vars($_POST);

					$rating = $_POST['rating'];

					$postId = $_POST['postId'];

					$post = $this->postRepository->getPost($postId);

					if(false != isUserLoggedIn()){

						$user = isUserLoggedIn();

						if(null != $post){

							if($user->getId() != $post->getAuthor()->getId() || ($post->getIsAnonymous() == true)){

								$isRated = $this->ratingLogRepository->checkLogForUser($user,$post);

								if($isRated){

									if(null != $post->getPostDetails()){

										$postDetails = $post->getPostDetails();

										if(false != $postDetails){
											$actualRating = 0;

											$reporter_level = 1;
											if($rating == -3){
												$actualRating = 3 * $reporter_level;
											}elseif ($rating == -1) {
												$actualRating = 1 * $reporter_level;
											}elseif($rating == 0.5){
												$actualRating = 1 * $reporter_level;
											}elseif ($rating == 1.5) {
												$actualRating = 2 * $reporter_level;
											}elseif ($rating == 3) {
												$actualRating = 4 * $reporter_level;
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
											$ratingLog->setRating($rating);

											$this->doctrine->em->persist($ratingLog);

											$penalty = 1;
											$halflife = 24;

											$positive_sum = $postDetails->getPositive_sum();
											$negative_sum = $postDetails->getNegative_sum();

											$exp1 = ($positive_sum + 1.9208)/($positive_sum + $negative_sum);
											// $exp21 = ($positive_sum + $negative_sum);

											$exp21 = ($positive_sum * $negative_sum)/($positive_sum + $negative_sum);
											$exp21 = $exp21 + 0.9604;
											$exp2 = (1.96 * sqrt($exp21))/($positive_sum + $negative_sum);

											$exp3 = 1 + 3.8416/($positive_sum + $negative_sum);

											$exp = ($exp1 - $exp2)/$exp3;

											$averageRating = $exp * $penalty;

											$this->load->helper('date');

											$date = new DateTime();
											$date = $date->getTimestamp();

											$createdDate = $post->getCreatedOn();
											$createdDate = $createdDate->getTimestamp();

											$interval = $date-$createdDate;

											$articleLife = $interval/3600;

											$postRank = $averageRating * log((1 + $postDetails->getNumberOfViews())/pow(2, ($articleLife/$halflife)));

											$post->setPostRanking($postRank);

											$postDetails->setRating($averageRating);

											$this->doctrine->em->persist($postDetails);

											$this->doctrine->em->persist($post);
											$this->doctrine->em->flush();


											$response->setSuccess(true);
											$response->setData(array('msg'=>'Rated'));
											$response->setError('');
										}else{
											$response->setSuccess(false);
											$response->setData('');
											$response->setError(array('msg'=>'We are facing some technical issues. Please check back later.'));	
										}
									}else{
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array('msg'=>'We are facing some technical issues. Please check back later.'));
									}
								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array('msg'=>'You have already rated the post'));
								}
							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array(
									'code'=>'7003',
									'msg'=>'Rating your own post is not allowed')
									);
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Post not found'));	
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User must be logged in'));
					}
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
			$response->setError(array(
				'msg'=>'Cross domain requests are not allowed'
			));
		}

		$response->respond();
		die();
	}

	function getRatingForPost(){
		$response = new Response();
		if(false != checkCsrf()){
			try {

				if($_SERVER['REQUEST_METHOD'] == "POST"){

					$_POST = file_get_contents("php://input");

					$post = $this->postRepository->getPost($_POST);

					if(false != $post){
						// $postDetails = $this->postDetailsRepository->findOneBy(array('post'=>$post));
						$postDetails = $post->getPostDetails();
						
						if(false != $postDetails){
							$ratingValue = $postDetails->getRating();

							if(null != $ratingValue){
								$ratingValue = $ratingValue;
							}else{
								$ratingValue = 0;
							}

							$data = array();

							$data['ratingValue'] = $ratingValue;

							if($postDetails->getNumberOfRatingUsers() == null){
								$data['numberOfRates'] = 0;
							}else{
								$data['numberOfRates'] = $postDetails->getNumberOfRatingUsers();
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

							// $averageRating = round($averageRating/$data['numberOfRates'],4);

							$response->setSuccess(true);
							$response->setData($averageRating);
							$response->setError('');

						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Post Details not found'));
						}

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'Post not found'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
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

	function saveImpact(){
		$response = new Response();
	
		if(false != checkCsrf()){
			try {
				if(false != isUserLoggedIn()){

					$user = isUserLoggedIn();

					if($_SERVER['REQUEST_METHOD'] == "POST"){
						$_POST = file_get_contents("php://input");
						$_POST = json_decode($_POST);
						$_POST = get_object_vars($_POST);

						$post = $this->postRepository->getPost($_POST['id']);

						if(false != $post){
							$ratingLog = $this->doctrine->em->getRepository('Entity\RatingLog')->findOneBy(array('post'=>$post,'user'=>$user));

							if(null != $ratingLog){
								$userImpact = $this->doctrine->em->getRepository('Entity\Impact')->findOneBy(array('area'=>$_POST['impact']));

								if(null != $userImpact){
									$ratingLog->setUserImpact($userImpact);

									$postRatings = $this->doctrine->em->getRepository('Entity\RatingLog')->findBy(array('post'=>$post));

									//if(count($postRatings) > 50){
										
									//}

									$this->doctrine->em->persist($ratingLog);
									$this->doctrine->em->flush();
									$this->calculateImpacts($post,$userImpact, $postRatings);

									$response->setSuccess(true);
									$response->setData(array('msg'=>'Impact recorded'));
									$response->setError('');
								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array('msg'=>'Impact error'));	
								}
							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array('msg'=>'Rating log for user and post not found'));
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Post not found'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'Method Error'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'User not logged in'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}


	function calculateImpacts($post,$userImpact, $postRatings){

		$impactRatings = $this->doctrine->em->getRepository('Entity\RatingLog')->findBy(array('post'=>$post, 'userImpact'=>$userImpact));
		$finalMax = 0;
		$percent = count($impactRatings)/count($postRatings);
		$percent = $percent * 100;
		if($percent < 25){
			$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();
			$counts = array();
			foreach ($impacts as $impac) {
				if($impac->getId() == $post->getUserImpact()->getId()) {
					continue;
				}
				$impactLog = $this->doctrine->em->getRepository('Entity\RatingLog')->findBy(array('post'=>$post,'userImpact'=>$impac));
				if(!isset($counts[$impac->getArea()])) {
					$counts[$impac->getId()] = 0;
				}
				$counts[$impac->getId()] = count($impactLog);
			}

			$max = 0;
			$maxKey = "";
			foreach ($counts as $key => $value) {
				if($value > $max) {
					$max = $value;
					$maxKey = $key;
				}
			}

			if(count($impactRatings) > $max) {
				$finalMax = $userImpact; // max impact
			} else {
				$impact = $this->doctrine->em->getRepository('Entity\Impact')->findOneBy(array('id' => $maxKey));
				$finalMax = $impact; // max impact
			}


			$post->setUserImpact($finalMax);

			$this->doctrine->em->persist($post);
			$this->doctrine->em->flush();
			
			return true;
		}
	}


	function share(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$_POST = file_get_contents("php://input");
				$_POST = json_decode($_POST);

				$post = $this->postRepository->getPost($_POST);

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
