<?php
use Entity\UserLocations;
class SettingsController extends CI_Controller {
	var $userRepository;
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
 		$this->load->helper('api');
 	}
 
 	function index(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(false != checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							if($_SERVER['REQUEST_METHOD'] == "POST"){
 								$user = checkAuthToken();
 								
 								extract($_POST);

 								if(null !=$_FILES){

 									// echo "<pre>";
 									// print_r($_FILES);
 									// die();

									// $this->load->helper('file');

									// $file_ary = deviceArrayFiles($_FILES);

									// foreach ($file_ary as $image) {

										// $_FILES['images']['name']= $image['name'];
							   //          $_FILES['images']['type']= $image['type'];
							   //          $_FILES['images']['tmp_name']= $image['tmp_name'];
							   //          $_FILES['images']['error']= $image['error'];
							   //          $_FILES['images']['size']= $image['size'];
										
									$config ['upload_path'] = './uploads/profilepictures/';
									$config ['allowed_types'] = 'jpeg|png|jpg';
									$config ['max_size'] = '10000'; //10MB  /*What is the limit?*/
										
									$this->load->library ( 'upload', $config );
												
									if (!$this->upload->do_upload('profilePicture')) {
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array('msg' => $this->upload->display_errors()));
										$response->respond();
										die();
									} else {
										$data = $this->upload->data();
										$user->setProfilePicturePath(base_url()."uploads/profilepictures/".$data['file_name']);
									}
									// }
								}

 								$user->setUserName($reporterHandle);
 								$user->setFirstName($firstName);
 								$user->setLastName($lastName);
 								$user->setAbout($about);
 								$user->setContactNumber($contactNumber);
 								$user->setGender($gender);
 								$user->setCountry($country);
 								$user->setCity($city);

 								$this->doctrine->em->persist($user);
 								$this->doctrine->em->flush();

 								$response->setSuccess(true);
 								$response->setData(array(
 									'code'=>'',
 									'msg'=>'Information saved'
 								));
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
 								'code'=>2002,
 								'msg'=>'AUth token mismatch'
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

 	function getPreferenceSettingsData(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){

 							$user = checkAuthToken();

 							$data = array();

 							$data['id'] = $user->getId();
 							$data['userName'] = $user->getUserName();
 							$data['email'] = $user->getEmail();
 							if($user->getFirstName() == null){
 								$data['firstName'] = "";
 							}else{
 								$data['firstName'] = $user->getFirstName();
 							}

 							if($user->getLastName() == null){
 								$data['lastName'] = "";
 							}else{
 								$data['lastName'] = $user->getLastName();
 							}

 							if($user->getProfilePicturePath() == null){
 								$data['picture'] = "";
 							}else{
 								$data['picture'] = $user->getProfilePicturePath();
 							}

 							if($user->getGender() == null){
 								$data['gender'] = "";
 							}else{
 								$data['gender'] = $user->getGender();
 							}

 							if($user->getCountry() == null){
 								$data['country'] = "";
 							}else{
 								$data['country'] = $user->getCountry();
 							}
 							
 							if($user->getCity() == null){
 								$data['city'] = "";
 							}else{
 								$data['city'] = $user->getCity();
 							}

 							if($user->getAbout() == null){
 								$data['about'] = "";
 							}else{
 								$data['about'] = $user->getAbout();
 							}

 							if($user->getContactNumber() == null){
 								$data['contactNumber'] = "";
 							}else{
 								$data['contactNumber'] = $user->getContactNumber();
 							}
 							

 							$userCategoryData = array();
							$categoriesData = array();

							$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();
							
							foreach ($user->getUser_categoryPreference() as $categoryPreference) {
								$userCategoryData[] = $categoryPreference->getId();
							}

							foreach ($categories as $category) {
								if(in_array($category->getId(), $userCategoryData)){
									$temp['id'] = $category->getId();
									$temp['name'] = $category->getName();
									$temp['set'] = true;
								}else{
									$temp['id'] = $category->getId();
									$temp['name'] = $category->getName();
									$temp['set'] = false;
								}

								$data['userCategories'][] = $temp;
							}

							$data['userLocations'] = array();

							if(!is_null($user->getUser_locations())){
								foreach ($user->getUser_locations() as $userLocations) {
									$temp2['id'] = $userLocations->getId();
									$temp2['location'] = $userLocations->getLocationName();
									$data['userLocations'][] = $temp2;
								}
							}else{
								$data['userLocations'] = array();
							}

							$emailNotification = $this->doctrine->em->getRepository('Entity\Notifications')->findOneBy(array('type'=>'EMAIL'));

							$mobileNotification = $this->doctrine->em->getRepository('Entity\Notifications')->findOneBy(array('type'=>'MOBILE'));

							$isEmail = false;
							$isMobile = false;
							if(!$user->getUser_notifications()->isEmpty()){
								if(null != $emailNotification){
									foreach ($user->getUser_notifications() as $eNotify) {
										if($eNotify == $emailNotification){
											$isEmail = true;
										}
									}
								}

								if (null != $mobileNotification) {
									foreach ($user->getUser_notifications() as $mNotify) {
										if($mNotify == $mobileNotification){
											$isMobile = true;
										}
									}
								}

							}

							// $data['userNotifications'][] = $temp3;
							$data['emailNotification'] = $isEmail;
							$data['mobileNotification'] = $isMobile;

							// if(!$user->getUser_notifications()->isEmpty()){

							// 	foreach ($user->getUser_notifications() as $notifications) {
							// 		$temp3['id'] = $notifications->getId();
							// 		$temp3['type'] = $notifications->getType();

							// 		$data['userNotifications'][] = $temp3;
							// 	}
							// }

							$response->setSuccess(true);
							$response->setData($data);
							$response->setError('');

 						}else{
 							$response->setSuccess(false);
 							$response->setData('');
 							$response->setError(array(
 								'code'=>2001,
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

 	function preferenceSave(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							$user = checkAuthToken();

 							if($_SERVER['REQUEST_METHOD'] == "POST"){
 								extract($_POST);

 								$user->getUser_categoryPreference()->clear();

 								$this->doctrine->em->persist($user);
 								$this->doctrine->em->flush();

 								$_POST['categories'] = explode(',', $_POST['categories']);
								array_pop($_POST['categories']);

 								foreach ($_POST['categories'] as $categId) {
 									$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id'=>$categId));
 									if(!is_null($category)){
 										$user->addUser_categoryPreference($category);
 										$this->doctrine->em->persist($user);
 									}else{
 										$response->setSuccess(false);
 										$response->setData('');
 										$response->setError(array(
 											'code'=>'',
 											'msg'=>'Category not found'
 										));

 										$response->respond();
 										die();
 									}
 								}

 								$user->getUser_locations()->clear();

 								$this->doctrine->em->persist($user);
 								$this->doctrine->em->flush();

 								$locations = explode(',', $_POST['locations']);
 								array_pop($locations);

 								if(null != $locations){
									// if($user->getUser_locations()->isEmpty() == 1){
										// foreach ($user->getUser_locations() as $userLocations) {
										// 	$this->doctrine->em->remove($userLocations);
										// 	$this->doctrine->em->persist($user);
										// }

										$user->getUser_locations()->clear();

										$userLocs = $this->doctrine->em->getRepository('Entity\UserLocations')->findBy(array('user'=>$user));

										foreach ($userLocs as $loc) {
											// $user->getUser_locations()->removeElement($loc);
											$this->doctrine->em->remove($loc);
										}

										$this->doctrine->em->persist($user);
										$this->doctrine->em->flush();
									// }


									
									foreach ($locations as $location) {
										$locat = new UserLocations();
										$locat->setLocationName($location);
										$locat->setUser($user);
										$this->doctrine->em->persist($locat);

										$user->addUser_locations($locat);
										$this->doctrine->em->persist($user);
									}
								}

								$user->getUser_notifications()->clear();

								$this->doctrine->em->persist($user);
								$this->doctrine->em->flush();

								$emailNotification = $this->doctrine->em->getRepository('Entity\Notifications')->findOneBy(array('type'=>'EMAIL'));

								if($_POST['emailNotification'] == "true"){
									
									$user->addUser_notifications($emailNotification);
									$this->doctrine->em->persist($user);
								}else{

									$user->getUser_notifications()->removeElement($emailNotification);
									$this->doctrine->em->persist($user);
								}

								$mobileNotification = $this->doctrine->em->getRepository('Entity\Notifications')->findOneBy(array('type'=>'MOBILE'));

								if ($_POST['mobileNotification'] == "true") {
									
									$user->addUser_notifications($mobileNotification);
									$this->doctrine->em->persist($user);
								}else{
						
									$user->getUser_notifications()->removeElement($mobileNotification);
									$this->doctrine->em->persist($user);
								}

								// $user->setTimeZone($_POST['timeZone']);

								$this->doctrine->em->persist($user);

 								$this->doctrine->em->flush();

 								$response->setSuccess(true);
 								$response->setData(array('msg'=>'Preference Settings saved'));
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
 								'code'=>2002,
 								'msg'=>'Auth token did not match'
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

 	public function preferenceSettings(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){

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
 				'msg'=>$e->getMessage()
 			));
 		}
 		$response->respond();
 		die();
 	}

 } ?>