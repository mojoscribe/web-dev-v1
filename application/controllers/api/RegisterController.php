<?php
use Entity\User;
use Entity\UserVerification;
class RegisterController extends CI_Controller {
	var $userRepository;
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->load->file ('application/classes/mailer/class.phpmailer.php');
		$this->load->file ('application/classes/mailer/class.smtp.php');
		$this->load->file ('application/classes/mailer/PHPMailerAutoload.php');
		$this->load->helper('api');
 		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
 	}
 
 	function index(){
 		error_reporting(0);
 		$response = new Response();
 		if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['userName']) && isset($_POST['email']) && isset($_POST['password'])) {
 			// $headers = apache_request_headers();
 			extract($_POST);

	 		try {
	 			if(checkApiKey()){

	 				$userVerifyEmail = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('email'=>$email));
		 			
		 			if(null == $userVerifyEmail){
				 		
				 		$verifyUserName = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('userName'=>$userName));
		 				if(null == $verifyUserName){
				 			$user = new User();
				 			$user->setEmail($email);
				 			$user->setUserName($userName);
				 			$user->setPassword(md5($password));
				 			$user->setCredibilityPoints(0);
				 			$user->setIsbannedStatus(0);
				 			$user->setIsVerified(1);
				 			$user->setHasSeen(0);
				 			$user->setGcmId($gcmId);

				 			$authToken = generateAuthToken();
				 			$user->setAuthToken($authToken);

				 			$this->doctrine->em->persist($user);

				 			$userVerification = new UserVerification();
							$userVerification->setUser($user);
							$timeStamp = time();
							$verificationLink = md5($timeStamp);
							$userVerification->setVerificationLink($verificationLink);

							$this->doctrine->em->persist($userVerification);
				 			$this->doctrine->em->flush();

				 			$mail = new PHPMailer ();
						
							$mail->isSMTP (); // Set mailer to use SMTP
							$this->mailer->Host = 'smtp.mandrillapp.com';
							$this->mailer->Port= 587;
							$this->mailer->SMTPAuth = true;
							$this->mailer->Username = 'mojoscribeteam@gmail.com'; // SMTP username
							$this->mailer->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
							//$this->mailer->SMTPSecure = 'ssl';
							
							$mail->From = 'admin@mojoscribe.com';
							$mail->FromName = 'Mojo-Scribe';
							$mail->addAddress ( $_POST ['email'] ); // Add a recipient
							
							$mail->WordWrap = 50; // Set word wrap to 50 characters
							$mail->isHTML ( true ); // Set email format to HTML
							
							$mail->Subject = "Registration at Mojo-Scribe";
							$mail->Body = "You have been successfully registered at Mojo-Scribe."."<br>";
							$mail->Body .= "To verify your account please follow the link below:<br><br>";
							$mail->Body .= "Verification Link: " .base_url()."verify?id=".$verificationLink."<br><br><br>";
							$mail->Body .= "This is an auto-generated email. Please do not reply to this e-mail";
							
							$mail->send ();

							$data = array(
								'userId'=>$user->getId(),
								'email'=>$user->getEmail(),
								'userName'=>$user->getUserName(),
								'authToken'=>$user->getAuthToken()
							);

							$response->setSuccess(true);
							$response->setData($data);
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'UserName has already been registered'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'1002',
							'message'=>'User already registered'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'1099',
						'message'=>'Invalid API_KEY'));
				}

	 		} catch (Exception $e) {
	 			$response->setSuccess(false);
	 			$response->setData(null);
	 			$response->setError(array('message'=>'Oops! Something went wrong! We are looking into it!'));
	 		}
 		}else{
 			$response->setSuccess(false);
 			$response->setData(null);
 			$response->setError(array(
 				'code'=>'1100',
 				'message'=>'Method Error'));
 		}
 		$response->respond();
 		die();
 	}
 } ?>