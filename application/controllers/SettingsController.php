<?php 
use Entity\UserLocations;
use Entity\Notifications;
class SettingsController extends CI_Controller {
	var $categoryRepository;
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
		$this->categoryRepository = $this->doctrine->em->getRepository('Entity\Category');
		$this->load->file ('application/classes/mailer/class.phpmailer.php');
		$this->load->file ('application/classes/mailer/class.smtp.php');
		$this->load->file ('application/classes/mailer/PHPMailerAutoload.php');
	}

	function index(){
		try {
			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();

				$email = $user->getEmail();
				$profile['id'] = $user->getId();
				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();
				
				$this->load->view('header',array('data'=>$profile));
				// $this->load->view('user/header',array('data'=>$profile));
				$this->load->view('user/sidebar',array('profile'=>$profile));
				$this->load->view('user/editProfile');
				$this->load->view('footer',array('scripts'=>array('controllers/profileSettingscontroller.js')));
			}else{
				redirect('/?sessionexpired');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function checkHasSeen(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if(isUserLoggedIn()){
					$user = isUserLoggedIn();

					if($user->getHasSeen() == true){
						$response->setSuccess(true);
						$response->setData(array('msg'=>'Has seen','code'=>1));
						$response->setError('');
					}else{
						$response->setSuccess(true);
						$response->setData(array('msg'=>'Has not seen','code'=>2));
						$response->setError('');
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'User is not logged in'
					));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
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

	function messageSeen(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if(isUserLoggedIn()){
					$user = isUserLoggedIn();

					$user->setHasSeen(true);

					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();

					$response->setSuccess(true);
					$response->setData(array('msg'=>'Made seen'));
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'User is not logged in'
					));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
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

	function preferences(){
		try {
			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();
				$data = array();

				$profile['id'] = $user->getId();
				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$profile));
				// $this->load->view('user/header',array('data'=>$profile));
				$this->load->view('user/sidebar');
				$this->load->view('user/preferencesPage',array('userData'=>$profile));
				$this->load->view('footer',array('scripts'=>array('controllers/preferencesPagecontroller.js')));
			}else{
				redirect('/?sessionexpired');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function firstTimeSettings(){
		try {
			
			if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['userId'])){
				session_start();

				if(isset($_SESSION['userName']) || "" == $_SESSION['userName']){

					$loggedInUser = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_GET['userId']));

					$date = new DateTime();

					if($date->format('d-M-Y') == $loggedInUser->getCreatedOn()->format('d-M-Y')){

						if($loggedInUser->getId() == $user->getId() && $loggedInUser->getIsVerified() == 1){
							if(null != $user){
								$data = array();

								$data['id'] = $user->getId();
								$data['userName'] = $user->getUserName();

								$this->load->view('register-header');
								$this->load->view('user/firstTime/profileSettingsPage',array('userData'=>$data));
								$this->load->view('footer',array('scripts'=>array('controllers/settingsPagecontroller.js')));
							}else{
								redirect('error');
							}
						}else{

							redirect('settings');
						}
					}else{
						redirect('loginerror');
					}
				}else{
					
					redirect('/?sessionexpired');
				}
			}else{
				redirect('/');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function saveCategory(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "POST"){
					$_POST = file_get_contents('php://input');
					$_POST = json_decode($_POST);
					$_POST = get_object_vars($_POST);

					session_start();
					if(isset($_SESSION['userName']) || "" == $_SESSION['userName']){
						$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

						$isCategoryPresent = false;

						$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id'=>$_POST['id']));

						if(null != $category){
							if(!$user->getUser_categoryPreference()->isEmpty()){
								foreach ($user->getUser_categoryPreference() as $categoryPreference) {
									if($categoryPreference == $category){
										$user->getUser_categoryPreference()->removeElement($categoryPreference); /*New method found for removing elements from Many to Many relation*/
										// $this->doctrine->em->remove($categoryPreference);
										$this->doctrine->em->persist($user);
										$isCategoryPresent = true;
									}
								}
							}

							if(false == $isCategoryPresent){
								$user->addUser_categoryPreference($category);
								$this->doctrine->em->persist($user);
							}

							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array('msg'=>'Saved'));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Category was not found'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User is not logged in'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
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

	function saveMobileNotification(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "GET"){
					session_start();
					if(isset($_SESSION['id'])){
						$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

						$mobileNotification = $this->doctrine->em->getRepository('Entity\Notifications')->findOneBy(array('type'=>'MOBILE'));

						$isNotificationPresent = false;
						if(null != $mobileNotification){
							if(!$user->getUser_notifications()->isEmpty()){
								foreach ($user->getUser_notifications() as $notification) {
									if($notification == $mobileNotification){
										$user->getUser_notifications()->removeElement($notification);
										$this->doctrine->em->persist($user);
										$isNotificationPresent = true;
									}

								}
							}

							if(false == $isNotificationPresent){
								$user->addUser_notifications($mobileNotification);
								$this->doctrine->em->persist($user);
							}
							
							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array('msg'=>'Saved'));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Notification error'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User is not logged in'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function saveEmailNotification(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "GET"){
					// session_start();
					if(isset($_SESSION['id'])){
						$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

						$emailNotification = $this->doctrine->em->getRepository('Entity\Notifications')->findOneBy(array('type'=>'EMAIL'));

						$isNotificationPresent = false;
						if(null != $emailNotification){
							if(!$user->getUser_notifications()->isEmpty()){
								foreach ($user->getUser_notifications() as $notification) {
									if($notification == $emailNotification){
										$user->getUser_notifications()->removeElement($notification);
										// $this->doctrine->em->remove($notification);
										$this->doctrine->em->persist($user);
										$isNotificationPresent = true;
									}

								}
							}

							if(false == $isNotificationPresent){
								$user->addUser_notifications($emailNotification);
								$this->doctrine->em->persist($user);
							}
							
							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array('msg'=>'Saved'));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Notification error'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User is not logged in'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function preferencesSettings(){
		try {
			
			if($_SERVER['REQUEST_METHOD'] == "GET"){
				session_start();
				if(isset($_SESSION['userName'])){
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

					$profile['userName'] = $user->getUserName();
					$profile['picture'] = $user->getProfilePicturePath();

					$data = array();
					$data['id'] = $_GET['id'];

					$this->load->view('register-header',array('data'=>$profile));
					// $this->load->view('user/header',array('data'=>$profile));
					$this->load->view('user/firstTime/preferencesSettingsPage',array('userData'=>$data));
					$this->load->view('footer',array('scripts'=>array('select.js','controllers/preferencesPagecontroller.js')));
				}else{
					redirect('/?sessionexpired');
				}
			}else{
				redirect('/');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	public function getCategories(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();

				// session_start();
				if(isUserLoggedIn()){
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

					$userCategoryData = array();
					$categoriesData = array();
					
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

						$data[] = $temp;
					}

					$response->setSuccess(true);
					$response->setData($data);
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'User has logged out'));
				}

			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}

		$response->respond();
		die();
	}

	function getEmail(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				// session_start();
				if(isUserLoggedIn()){
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_SESSION['id']));

					$data = array();
					$data['email'] = $user->getEmail();

					// echo "<pre>";
					// print_r($data);
					// die();

					$response->setSuccess(true);
					$response->setData($data);
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'User is not logged in'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>$e->getMessage()));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function saveProfile(){
		$response = new Response();
		if(false != checkCsrf()){

			$profile = json_decode($_POST['profile']);
			$profile = get_object_vars($profile);
			
			try {
				if($_SERVER['REQUEST_METHOD'] == "POST"){
					$user = $this->doctrine->em->getRepository('Entity\User')->getUser($profile['id']);

					if(null != $user){


						if(null !=$_FILES){

							$this->load->helper('file');

							$file_ary = profilePicFiles($_FILES);

							foreach ($file_ary as $image) {

								$_FILES['images']['name']= $image['name'];
					            $_FILES['images']['type']= $image['type'];
					            $_FILES['images']['tmp_name']= $image['tmp_name'];
					            $_FILES['images']['error']= $image['error'];
					            $_FILES['images']['size']= $image['size'];
								
								$config ['upload_path'] = './uploads/profilepictures/';
								$config ['allowed_types'] = 'jpeg|png|jpg';
								$config ['max_size'] = '10000'; //10MB  /*What is the limit?*/
									
								$this->load->library ( 'upload', $config );
											
								if (!$this->upload->do_upload('images')) {
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array('msg' => $this->upload->display_errors()));
									$response->respond();
									die();
								} else {
									$data = $this->upload->data();
									$user->setProfilePicturePath(base_url()."uploads/profilepictures/".$data['file_name']);
								}
							}
						}

						$user->setUserName(strip_tags($profile['reporterHandle']));
						$user->setFirstName(strip_tags($profile['firstName']));
						$user->setLastName(strip_tags($profile['lastName']));
						$user->setGender(strip_tags($profile['gender']));
						$user->setCountry(strip_tags($profile['country']));
						$user->setCity(strip_tags($profile['city']));
						$user->setAbout(strip_tags($profile['about']));
						$user->setContactNumber(strip_tags($profile['contactNo']));

						$this->doctrine->em->persist($user);
						$this->doctrine->em->flush();

						$_SESSION['id'] = $user->getId();
						$_SESSION['userName'] = $user->getUserName();
						$_SESSION['email'] = $user->getEmail();

						$response->setSuccess(true);
						$response->setData(array('msg'=>'Information Saved'));
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'We were unable to find the user you are looking for'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function preferencesSettingsSave(){
		$response = new Response();
		if(false != checkCsrf()){
			try {

				$locations = array();
				if(null != $_POST['locations']){
					$locations = explode(',', $_POST['locations']);
				}

				// session_start();
				if(isUserLoggedIn()){
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_POST['userId']));

					if(null != $user){

						if(!$user->getUser_locations()->isEmpty()){
							foreach ($user->getUser_locations() as $userLocations) {
								$this->doctrine->em->remove($userLocations);
								$this->doctrine->em->persist($user);
							}
						}
						if(null != $locations){
							
							foreach ($locations as $location) {
								$locat = new UserLocations();
								$locat->setLocationName($location);
								$locat->setUser($user);
								$this->doctrine->em->persist($locat);

								$user->addUser_locations($locat);
								$this->doctrine->em->persist($user);
							}
						}

						$user->setTimeZone($_POST['timeZone']);

						$user->setIsVerified(1);

						$this->doctrine->em->persist($user);
						$this->doctrine->em->flush();

						$response->setSuccess(true);
						$response->setData(array('msg'=>'Saved'));
						$response->setError('');

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'We were unable to find the user you are looking for'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'The user you are looking for has been logged out of the system. Please login to continue'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>$e->getMessage()));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	public function getUser(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])){
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_GET['id']));

					if(null != $user){
						$data = array();
						$data['id'] = $user->getId();
						$data['userName'] = $user->getUserName();
						//if(null != $user->getFirstName()){
							$data['firstName'] = $user->getFirstName();
						//}

						//if(null != $user->getLastName()){
							$data['lastName'] = $user->getLastName();
						//}

						//if(null != $user->getAbout()){
							$data['about'] = $user->getAbout();
						//}

						//if(null != $user->getCity()){
							$data['city'] = $user->getCity();
						//}

						//if(null != $user->getCountry()){
							$data['country'] = $user->getCountry();
						//}

						//if(null != $user->getGender()){
							$data['gender'] = $user->getGender();
						//}

						//if(null != $user->getContactNumber()){
							$data['contactNo'] = $user->getContactNumber();
						//}
							$data['profilePicUrl'] = $user->getProfilePicturePath();
						$response->setSuccess(true);
						$response->setData($data);
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'We were unable to find the user you were looking for'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function changeEmail(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['userId'])) {
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_GET['userId']));

					if(null != $user){
						if($_GET['email'] != $user->getEmail()){

							$user->setEmail($_GET['email']);
							$this->doctrine->em->persist($user);
							$this->doctrine->em->flush();

							$response->setSuccess(true);
							$response->setData(array('msg'=>'Registered'));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Email same'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'We were unable to find the user you were looking for'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>$e->getMessage()));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function getUserPreferences(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if(false != isUserLoggedIn()){
					$user = isUserLoggedIn();

					$data = array();

					foreach ($user->getUser_categoryPreference() as $categoryPreference) {
						$data['categoryPreferences'][] = $categoryPreference->getName();
					}

					$data['userLocations'] = array();
					foreach ($user->getUser_locations() as $userLocations) {
						$data['userLocations'][] = $userLocations->getLocationName();
					}

					$data['notifications']['mobile'] = false;
					$data['notifications']['email'] = false;

					foreach ($user->getUser_notifications() as $notifications) {
						if($notifications->getType() == "MOBILE"){
							$data['notifications']['mobile'] = true;
						}elseif($notifications->getType() == "EMAIL"){
							$data['notifications']['email'] = true;
						}
					}

					$data['timeZone'] = $user->getTimeZone();

					$response->setSuccess(true);
					$response->setData($data);
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'The user you are looking for has been logged out of the system. Please login to continue'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>$e->getMessage()));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}
		$response->respond();
		die();
	}

	function contactSubmit(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$_POST = file_get_contents("php://input");
				$_POST = json_decode($_POST);
				$_POST = get_object_vars($_POST);
				
				extract($_POST);

 				$mail = new PHPMailer ();
			
				$mail->isSMTP (); // Set mailer to use SMTP
				$mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
				$mail->Port = 465;
				$mail->SMTPAuth = true; // Enable SMTP authentication
				$mail->Username = 'mojoscribeteam@gmail.com'; // SMTP username
				$mail->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
				$mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
				//$this->mailer->SMTPSecure = 'ssl';
				
				$mail->From = strip_tags($_POST['email']);
				$mail->FromName = strip_tags($_POST['name']);
				$mail->addAddress ( "admin@mojoscribe.com" ); // Add a recipient
				
				$mail->WordWrap = 50; // Set word wrap to 50 characters
				$mail->isHTML ( true ); // Set email format to HTML
				
				$mail->Subject = "Contact at Mojo-Scribe";
				$mail->Body = strip_tags($_POST['message']);
				$mail->Body .= "<br>. Contact Number: ".$_POST['phone'];
				
				$mail->send ();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'Ticket Raised'));
				$response->setError('');
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

	function termsAndConditions(){

		if(isUserLoggedIn()){

			$user = isUserLoggedIn();

			$profile['userName'] = $user->getUserName();
			$profile['picture'] = $user->getProfilePicturePath();

			$this->load->view('header',array('data'=>$profile));
		}else{
			$this->load->view('header');
		}
		
		$this->load->view('termsnconditions');
		$this->load->view('footer');
	}

	function privacyPolicy(){

		if(isUserLoggedIn()){

			$user = isUserLoggedIn();

			$profile['userName'] = $user->getUserName();
			$profile['picture'] = $user->getProfilePicturePath();

			$this->load->view('header',array('data'=>$profile));
		}else{
			$this->load->view('header');
		}
		
		$this->load->view('privacypolicy.htm');
		$this->load->view('footer');
	}
}
?>