<?php
use Entity\User;
class LoginController extends CI_Controller {
	var $userRepository;
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->load->helper('api');
 		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
 		$this->load->file ('application/classes/mailer/class.phpmailer.php');
		$this->load->file ('application/classes/mailer/class.smtp.php');
		$this->load->file ('application/classes/mailer/PHPMailerAutoload.php');
 	}
 
 	function index(){
 		$response = new Response();
 		if($_SERVER['REQUEST_METHOD'] == "POST"){

	 		$headers = apache_request_headers();

	 		extract($_POST);
	 		try {
	 			if($headers['api-key'] == API_KEY && isset($headers['api-key'])) {
	 				$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('userName' => $userName));
	 				
	 				if(null != $user){

	 					$authenticate = $this->userRepository->authenticateUser($userName,$password);

	 					if(false != $authenticate){
				 			$bannedStatus = $this->userRepository->checkBannedStatus($user->getEmail());
				 			if(false != $bannedStatus){

				 				$user->setGcmId($gcmId);

				 				if($user->getAuthToken() == null){
				 					$authToken = generateAuthToken();
			 						$user->setAuthToken($authToken);
				 				}
				 					
				 				$this->doctrine->em->persist($user);
				 				$this->doctrine->em->flush();

				 				$response->setSuccess(true);
				 				$response->setData(array(
				 					'userId'=>$user->getId(),
				 					'authToken'=>$user->getAuthToken(),
				 					'userName'=>$user->getUserName(),
				 					'firstName'=>$user->getFirstName(),
				 					'lastName'=>$user->getLastName(),
				 					'profilePicture'=>$user->getProfilePicturePath()
				 				));
				 				$response->setError('');
				 			}else{
				 				$response->setSuccess(false);
				 				$response->setData('');
				 				$response->setError(array(
				 					'code'=>'2004',
				 					'message'=>'User Banned'));
				 			}
			 			}else{
			 				$response->setSuccess(false);
			 				$response->setData('');
			 				$response->setError(array(
			 					'code'=>'2003',
			 					'message'=>'User name and password do not match'));
			 			}
	 				}else{
	 					$response->setSuccess(false);
	 					$response->setData('');
	 					$response->setError(array(
	 						'code'=>'2002',
	 						'message'=>'User does not exist'));
	 				}
	 			}else{
	 				$response->setSuccess(false);
	 				$response->setData('');
	 				$response->setError(array(
	 					'code'=>'1099',
	 					'message'=>'Invalid API Key'));
	 			}
	 		} catch (Exception $e) {
	 			$response->setSuccess(false);
	 			$response->setData('');
	 			$response->setError(array('message'=>$e->getMessage()));
	 		}
 		}else{
 			$response->setSuccess(false);
 			$response->setData('');
 			$response->setError(array(
 				'code'=>'1100',
 				'message'=>'Method Error'));
 		}

 		$response->respond();
 		die();
 	}

 	function fbAuthenticate(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if($_SERVER['REQUEST_METHOD'] == "POST"){
 						extract($_POST);
						// $user = $this->$userRepository->checkUser($email);
						$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('email'=>$email));
						if($user){
							$bannedStatus = $this->userRepository->checkBannedStatus($user->getEmail());
							if($bannedStatus){
								// $user->setProfilePicturePath("http://graph.facebook.com/".$id."/picture?width=230&height=200");

								if($user->getAuthToken() == null){
				 					$authToken = generateAuthToken();
			 						$user->setAuthToken($authToken);
				 				}

				 				$user->setGcmId($gcmId);

								$this->doctrine->em->persist($user);
								$this->doctrine->em->flush();

								$data = array();

								$data['userId'] = $user->getId();
			 					$data['authToken'] = $user->getAuthToken();
			 					$data['userName'] = $user->getUserName();
			 					$data['firstName'] = $user->getFirstName();
			 					$data['lastName'] = $user->getLastName();
			 					$data['profilePicture'] = $user->getProfilePicturePath();
			 					$data['firstTime'] = false;

								$response->setSuccess(true);
								$response->setData($data);
								$response->setError('');
							}else{
								$response->setSuccess(false);
								$response->setError(array('msg'=>'User Banned'));
							}
						}else{
							$user = new User();
							$user->setEmail($email);
							$user->setGender($gender);
							$user->setFirstName($firstName);
							$user->setLastName($lastName);

							$this->load->helper ( 'string' );
							$password = random_string ( 'alnum' );

							$user->setPassword(md5($password));
							$user->setCredibilityPoints(0);
							$user->setIsbannedStatus(0);
							$user->setProfilePicturePath("http://graph.facebook.com/".$id."/picture?width=230&height=200");
							$user->setIsVerified(1);
							$user->setHasSeen(0);

		 					$authToken = generateAuthToken();
	 						$user->setAuthToken($authToken);
							
							$this->doctrine->em->persist($user);
							$this->doctrine->em->flush();

							$userName = $firstName.".".$lastName.$user->getId();
							$userName = strtolower($userName);

							$user->setUserName($userName);

							$this->doctrine->em->persist($user);
							$this->doctrine->em->flush();

							// $mail = new PHPMailer ();
						
							// $mail->isSMTP (); // Set mailer to use SMTP
							// $this->mailer->Host = 'smtp.mandrillapp.com';
							// $this->mailer->Port= 587;
							// $this->mailer->SMTPAuth = true;
							// $this->mailer->Username = 'varun1505@gmail.com';
							// $this->mailer->Password = 'IjpjcAULZL_o_bpT0KEgqA';
							
							// $mail->From = 'admin@mojoscribe.com';
							// $mail->FromName = 'Mojo-Scribe';
							// $mail->addAddress ( $_POST ['email'] ); // Add a recipient
							
							// $mail->WordWrap = 50; // Set word wrap to 50 characters
							// $mail->isHTML ( true ); // Set email format to HTML
							
							// $mail->Subject = "Registration at Mojo-Scribe";
							// $mail->Body = "You have been successfully registered at Mojo-Scribe."."<br>";
							// $mail->Body .= "To login, please enter your e-mail as mentioned below:<br><br>";
							// $mail->Body .= "E-mail ID: " .$_POST['email']."<br>";
							// $mail->Body .= "Password: " .$password. "<br>";
							// $mail->Body .= "You can change your password once you are logged in by choosing the 'Change Password' option from settings."."<br>";
							// $mail->Body .= "To login please click here <a href=".base_url().">MojoScribe </a> . Or copy and paste the link given below in your browser" . "<br>";
							// $mail->Body .= 
							// $mail->Body .= base_url();
							
							// $mail->send ();


							$data = array();

							$data['userId'] = $user->getId();
		 					$data['authToken'] = $user->getAuthToken();
		 					$data['userName'] = $user->getUserName();
		 					$data['firstName'] = $user->getFirstName();
		 					$data['lastName'] = $user->getLastName();
		 					$data['profilePicture'] = $user->getProfilePicturePath();
		 					$data['firstTime'] = true;

							$response->setSuccess(true);
							$response->setData($data);
							$response->setError('');
						}
 					}else{
 						$response->setSuccess(
 							false);
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
 				'msg'=>$e->getMessage()
 				));
 		}
 		$response->respond();
 		die();
 	}

 	function gPlusAuthenticate(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if($_SERVER['REQUEST_METHOD'] == "POST"){
 						extract($_POST);
						$user = $this->userRepository->checkUser($email);
						if($user){
							$bannedStatus = $this->userRepository->checkBannedStatus($user->getEmail());
							if($bannedStatus){
								// $picture = substr($imageUrl, 0,-2);

								// $pictureUrl = $picture."250";

								// $user->setProfilePicturePath($pictureUrl);

								if($user->getAuthToken() == null){
				 					$authToken = generateAuthToken();
			 						$user->setAuthToken($authToken);
				 				}

				 				$user->setGcmId($gcmId);

								$this->doctrine->em->persist($user);
								$this->doctrine->em->flush();

								$data = array();

								$data['userId'] = $user->getId();
			 					$data['authToken'] = $user->getAuthToken();
			 					$data['userName'] = $user->getUserName();
			 					$data['firstName'] = $user->getFirstName();
			 					$data['lastName'] = $user->getLastName();
			 					$data['profilePicture'] = $user->getProfilePicturePath();
			 					$data['firstTime'] = false;

								$response->setSuccess(true);
								$response->setData($data);
								$response->setError('');
							}else{
								$response->setSuccess(false);
								$response->setError(array('msg'=>'User Banned'));
							}
						}else{
							$user = new User();
							$user->setEmail($email);

							$nameArray = array();

							$nameArray = explode(" ", trim($name));

							$spaces=array();
							$expressions = array();

							foreach($nameArray as $word) {
								if($word == ' '){
									array_push($spaces,$word);
								}else{
									array_push($expressions,$word);
								}
							}

							$user->setFirstName($expressions[0]);
							$user->setLastName($expressions[1]);

							$this->load->helper ( 'string' );
							$password = random_string ( 'alnum' );

							$user->setPassword(md5($password));
							$user->setCredibilityPoints(0);
							$user->setIsbannedStatus(0);
							$picture = substr($imageUrl, 0,-2);

							$pictureUrl = $picture."250";

							$user->setProfilePicturePath($pictureUrl);
							$user->setIsVerified(1);
							$user->setHasSeen(0);

		 					$authToken = generateAuthToken();
	 						$user->setAuthToken($authToken);
							
							$this->doctrine->em->persist($user);
							$this->doctrine->em->flush();

							$userName = $expressions[0].".".$expressions[1].$user->getId();

							$user->setUserName($userName);

							$this->doctrine->em->persist($user);
							$this->doctrine->em->flush();

							// $mail = new PHPMailer ();
						
							// $mail->isSMTP (); // Set mailer to use SMTP
							// $this->mail->Host = 'smtp.mandrillapp.com';
							// $this->mail->Port= 587;
							// $this->mail->SMTPAuth = true;
							// $this->mail->Username = 'varun1505@gmail.com';
							// $this->mail->Password = 'IjpjcAULZL_o_bpT0KEgqA';
							
							// $mail->From = 'admin@mojoscribe.com';
							// $mail->FromName = 'Mojo-Scribe';
							// $mail->addAddress ( $_POST ['email'] ); // Add a recipient
							
							// $mail->WordWrap = 50; // Set word wrap to 50 characters
							// $mail->isHTML ( true ); // Set email format to HTML
							
							// $mail->Subject = "Registration at Mojo-Scribe";
							// $mail->Body = "You have been successfully registered at Mojo-Scribe."."<br>";
							// $mail->Body .= "To login, please enter your e-mail as mentioned below:<br><br>";
							// $mail->Body .= "E-mail ID: " .$_POST['email']."<br>";
							// $mail->Body .= "Password: " .$password. "<br>";
							// $mail->Body .= "You can change your password once you are logged in by choosing the 'Change Password' option from settings."."<br>";
							// $mail->Body .= "To login please click on the link below" . "<br>";
							// $mail->Body .= base_url();
							
							// $mail->send ();

							$data = array();

							$data['userId'] = $user->getId();
		 					$data['authToken'] = $user->getAuthToken();
		 					$data['userName'] = $user->getUserName();
		 					$data['firstName'] = $user->getFirstName();
		 					$data['lastName'] = $user->getLastName();
		 					$data['profilePicture'] = $user->getProfilePicturePath();
		 					$data['firstTime'] = true;

							$response->setSuccess(true);
							$response->setData($data);
							$response->setError('');
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
 				'msg'=>$e->getMessage()
 			));
 		}
 		$response->respond();
 		die();
 	}
}
?>