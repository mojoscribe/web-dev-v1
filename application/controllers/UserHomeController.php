<?php 
class UserHomeController extends CI_Controller {
	var $followRepository;
	var $postRepository;
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
		$this->followRepository = $this->doctrine->em->getRepository('Entity\Follow');
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
	}

	function index(){
		try {

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
					$data['userName'] = $user->getUserName();
					$data['picture'] = $user->getProfilePicturePath();

					$this -> load -> view('header',array('data'=>$data));
					// $this -> load -> view('user/header',array('data'=>$data));
					$this->load->view('navigation');
					$this->load->view('trendingView');
					$this->load->view('user/dashboard');
					$this->load->view('footer',array('scripts'=>array('controllers/userHomecontroller.js','controllers/trendingNewscontroller.js','trending.js')));
				} else {
					redirect('/?sessionexpired');
				}
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	// public function getSharedPostsOfPeopleUserFollows(){
	// 	$response = new Response();
	// 	if(false != checkCsrf()){
	// 		try {
	// 			if($_SERVER['REQUEST_METHOD'] == "GET"){

	// 				if(false != isUserLoggedIn()){
	// 					$user = isUserLoggedIn();

	// 					$subscriptionsOfUser = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('user'=>$user));

	// 					if(null != $subscriptionsOfUser){
	// 						$data = array();
	// 						foreach ($subscriptionsOfUser as $subscription) {
	// 							$sharedPost = $this->postRepository->getLatestPostForUser($subscription->getUser()->getId());
	// 							$temp['id'] = $sharedPost->getId();
	// 							$temp['title'] = $sharedPost->getHeadline();
	// 							$temp['files'] = array();
	// 							foreach ($sharedPost->getFiles() as $file) {
	// 								$tempFile = array();
	// 								$tempFile['thumb'] = base_url($file->getThumb());
	// 								$tempFile['small'] = base_url($file->getSmall());
	// 								//$tempFile['bigImage'] = base_url($file->getBigImage());
	// 								//$temp['files'][] = $file->getFilePath();
	// 								$temp['files'][] = $tempFile;
	// 								//$temp['files'][] = $file->getFilePath();
	// 							}
	// 							$temp['postType'] = $sharedPost->getPostType();
	// 							$temp['hashtags'] = array();
	// 							foreach ($sharedPost->getHashtags() as $hashtag) {
	// 								$temp['hashtags'][] = $hashtag->getHashtag();
	// 							}
	// 							$temp['date'] = $sharedPost->getCreatedOn()->format('d-M-Y');
	// 							$temp['author'] = $subscription->getUser()->getUserName();
	// 							$temp['slug'] = $sharedPost->getSlug();

	// 							$data[] = $temp;
	// 						}

	// 						$response->setSuccess(true);
	// 						$response->setData($data);
	// 						$response->setError('');
	// 					}else{
	// 						$response->setSuccess(false);
	// 						$response->setData('');
	// 						$response->setError(array('msg'=>'No Subscriptions'));
	// 					}
	// 				}else{
	// 					$response->setSuccess(false);
	// 					$response->setData('');
	// 					$response->setError(array('msg'=>'The user you are looking for has been logged out of the system. Please login to continue'));
	// 				}
	// 			}else{
	// 				$response->setSuccess(false);
	// 				$response->setData('');
	// 				$response->setError(array('msg'=>'Method Error'));
	// 			}
	// 		} catch (Exception $e) {
	// 			$response->setSuccess(false);
	// 			$response->setData('');
	// 			$response->setError(array('msg'=>$e->getMessage()));
	// 		}
	// 	}else{
	// 		$response->setSuccess(false);
	// 		$response->setData('');
	// 		$response->setError(array('msg'=>'Cross domain requests are not allowed'));
	// 	}

	// 	$response->respond();
	// 	die();
	// }


	function getPostsByCategory(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "POST"){
					if(false != isUserLoggedIn()){
						$user = isUserLoggedIn();

						if(!is_null($user->getUser_categoryPreference())){
							$preferenceData = array();
							foreach ($user->getUser_categoryPreference() as $preference) {
								$preferredPosts = $this->postRepository->getPostsByCategory($preference);

								$data = array();

								$count2 = count($preferredPosts);

								if($count2 >=6){
									$count2 = 6;
								}

								if(null != ($preferredPosts)){

									for ($i=0; $i < $count2; $i++) { 

										
										$temp['id'] = $preferredPosts[$i]->getId();
										$temp['headline'] = $preferredPosts[$i]->getHeadline();
										$temp['slug'] = $preferredPosts[$i]->getSlug();
										if($preferredPosts[$i]->getIsAnonymous() == true){
											$temp['author'] = "Anonymous";
										}else{
											$temp['author'] = $preferredPosts[$i]->getAuthor()->getUserName();
										}
										$temp['date'] = $preferredPosts[$i]->getUpdatedOn()->format('d-M-Y H:i:s');
										// $temp['date'] = strtotime($temp['date']);

										$temp['files'] = array();

										foreach ($preferredPosts[$i]->getFiles() as $file) {
											/*$tmp['id'] = $file->getId();
											$tmp['file'] = $file->getFilePath();

											$temp['files'][] = $tmp;*/

											$tempFile = array();
											$tempFile['thumb'] = base_url($file->getThumb());
											$tempFile['small'] = base_url($file->getSmall());
											$tempFile['bigImage'] = base_url($file->getBig());
											//$temp['files'][] = $file->getFilePath();
											$temp['files'][] = $tempFile;
										}

										$temp['description'] = $preferredPosts[$i]->getDescription();

										$temp['hashtags'] = array();

										foreach ($preferredPosts[$i]->getHashtags() as $hashtag) {
											$temp['hashtags'][] = $hashtag->getHashtag();
										}

										if($preferredPosts[$i]->getPostType() == "Video"){
											$temp['showVideo'] = true;
										}else{
											$temp['showVideo'] = false;
										}

										$data['id'] = $preference->getId();
										$data['name'] = $preference->getName();
										$data['posts'][] = $temp;
										
									}
								
									$preferenceData[] = $data;
								}
							}
							// die();

							$response->setSuccess(true);
							$response->setData($preferenceData);
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'',
								'msg'=>'Category Preferences not set for the user'
							));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'msg'=>'The user you are looking for has been logged out of the system. Please login to continue'
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

	function populateLocations(){
		try {
			$locations = $this->doctrine->em->getRepository('Entity\UserLocations')->findAll();

			foreach ($locations as $location) {
				$location = $this->doctrine->em->getRepository('Entity\UserLocations')->findOneBy(array('locationName'=>'Thane'));

				$findLoc = $this->doctrine->em->getRepository('Entity\City')->findOneBy(array('name'=>'Thane'));

				$location->setLongitude($findLoc->getLng());

				$location->setLatitude($findLoc->getLat());

				$this->doctrine->em->persist($location);
				$this->doctrine->em->flush();

			}

			echo "string";

		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}

	function getPostsByLocation(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(isUserLoggedIn()){
					$user = isUserLoggedIn();

					$locations = $this->doctrine->em->getRepository('Entity\UserLocations')->findBy(array('user'=>$user));

					if($locations != null){
						$locationPosts = array();
						foreach ($locations as $location) {
							// $posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array(),array('updatedOn'=>'desc'),100);

							$tempLng = $location->getLongitude();

							$tempLat = $location->getLatitude();

							$posts = $this->doctrine->em->getRepository('Entity\Post')->getLocationBasedPosts($location->getLocationName());

							if(null != $posts){
								foreach ($posts as $post) {
									$temp = array();
									
									$temp['id'] = $post->getId();

									$temp['headline'] = $post->getHeadline();
									if($post->getIsAnonymous() == true){
										$temp['author'] = "Anonymous";
									}else{
										$temp['author'] = $post->getAuthor()->getUserName();
									}

									$temp['slug'] = $post->getSlug();

									$temp['date'] = $post->getUpdatedOn()->format('d-M-Y H:i:s');

									$temp['files'] = array();

									foreach ($post->getFiles() as $file) {

										$tempFile = array();
										$tempFile['thumb'] = base_url($file->getThumb());
										$tempFile['small'] = base_url($file->getSmall());
										$tempFile['bigImage'] = base_url($file->getBig());
										$temp['files'][] = $tempFile;

									}

									$temp['dateObject'] = $post->getUpdatedOn();

									$temp['timeZone'] = '';

									if($post->getAuthor()->getTimeZone()){
										$temp['timeZone'] = $post->getAuthor()->getTimeZone();
									}

									if(null != $post->getLocation()){
										$tmpLoc = explode(',', $post->getLocation());

										if($tmpLoc[0] == $tmpLoc[1]){
											$temp['location'] = $tmpLoc[0];
										}else{
											$temp['location'] = $post->getLocation();
										}
									}

									$temp['hashtags'] = array();

									foreach ($post->getHashtags() as $hashtag) {
										$temp['hashtags'][] = $hashtag->getHashtag();
									}

									if($post->getPostType() == "Video"){
										$temp['showVideo'] = true;
									}else{
										$temp['showVideo'] = false;
									}

									$locationPosts[] = $temp;
								}
							}
						}

						$response->setSuccess(true);
						$response->setData($locationPosts);
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>10012,
							'msg'=>"Locations have not been entered"
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

	function reverseGeoCode(){
		try {
			$posts = $this->doctrine->em->getRepository('Entity\Post')->findAll();

			foreach ($posts as $post) {
				$reverse = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$post->getLatitude().",".$post->getLongitude());
				$reverse = json_decode($reverse,true);

				$data = array();

				$data = $reverse['results'][0]['address_components'];
				// echo "<pre>";
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

				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();
			}

			// echo "string";
		} catch (Exception $e) {
			
		}
	}

	function reverseLocations(){
		try {
			$posts = $this->doctrine->em->getRepository('Entity\Post')->findAll();

			foreach ($posts as $post) {
				// $coOrds = array();

				// $coOrds = explode(',', $post->getLocation());

				$latitude = $post->getLongitude();

				$longitude = $post->getLatitude();

				$post->setLocation($latitude.",".$longitude);

				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

			}

			echo "string";
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}

	function changeLocations(){
		try {
			$posts = $this->doctrine->em->getRepository('Entity\Post')->findAll();

			foreach ($posts as $post) {
				$coOrds = array();

				$coOrds = explode(',', $post->getLocation());

				$post->setLongitude($coOrds[1]);

				$post->setLatitude($coOrds[0]);

				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

			}

			echo "string";
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}
}
?>
