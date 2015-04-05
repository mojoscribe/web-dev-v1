<?php 
class LocationNewsController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
		$this->load->helper('api');
	}

	function index(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$headers = apache_request_headers();
				if(isset($headers['api-key'])){
	 				if(false != checkApiKey()){
	 					if(isset($headers['auth-token'])){
	 						if(checkAuthToken()){
	 							$user = checkAuthToken();

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

												if(null != $post->getLocation()){
													$tmpLoc = explode(',', $post->getLocation());

													if($tmpLoc[0] == $tmpLoc[1]){
														$temp['location'] = $tmpLoc[0];
													}else{
														$temp['location'] = $post->getLocation();
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
	 								'code'=>'',
	 								'msg'=>'User does not exist'
	 							));
	 						}
	 					}else{
	 						$response->setSuccess(false);
	 						$response->setData('');
	 						$response->setError(array(
	 							'code'=>'',
	 							'msg'=>'Auth Token not set'
	 						));
	 					}
	 				}else{
	 					$response->setSuccess(false);
	 					$response->setData('');
	 					$response->setError(array(
	 						'code'=>'',
	 						'msg'=>'Api Key incorrect'
	 					));
	 				}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'',
						'msg'=>'API Key not set'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'',
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



	function paginate(){
		$response = new Response();
		$headers = apache_request_headers();
		$data = array();

		try {
			if($headers['api-key'] == API_KEY && isset($headers['api-key'])) {

				if(isset($headers['auth-token'])){
					if(checkAuthToken()){
						$user = checkAuthToken();

						$locations = $this->doctrine->em->getRepository('Entity\UserLocations')->findBy(array('user'=>$user));

						if($_SERVER['REQUEST_METHOD'] == "POST"){
							if(isset($_POST['postIds'])){

								$locationPosts = array();
								foreach ($locations as $location) {

									$postIds = explode(',', $_POST['postIds']);
									array_pop($postIds);

									$tempLng = $location->getLongitude();

									$tempLat = $location->getLatitude();

									$posts = $this->doctrine->em->getRepository('Entity\Post')->getLocationBasedPosts($location->getLocationName());

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

										if(null != $posts){
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

												if(null != $post->getLocation()){
													$tmpLoc = explode(',', $post->getLocation());

													if($tmpLoc[0] == $tmpLoc[1]){
														$temp['location'] = $tmpLoc[0];
													}else{
														$temp['location'] = $post->getLocation();
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

												$locationPosts[] = $temp;
											}
										}

										$response->setSuccess(true);
										$response->setData($locationPosts);
										$response->setError('');

									}else{
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array(
											'code'=>'',
											'msg'=>'No Posts to display'
										));
									}
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
							'code'=>'',
							'msg'=>'Auth Token not correct'
						));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'',
						'msg'=>'Auth Token not set'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'1099',
					'msg'=>'Invalid API Key or Wrong API Key'));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}

		$response->respond();
		die();	
	}
}
?>