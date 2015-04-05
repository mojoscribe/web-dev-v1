<?php
use Entity\User;
class logincontroller extends CI_Controller {
	var $userRepository;
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
		$this->load->file ( 'application/classes/mailer/class.phpmailer.php' );
		$this->load->file ( 'application/classes/mailer/class.smtp.php' );
		$this->load->file ( 'application/classes/mailer/PHPMailerAutoload.php' );
		$this->load->file ('application/classes/GCM.php');
		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
	}

	function index(){
		$this->load->view('login.php');
	}

	function authenticate(){
		$response = new Response();
		$userRepository = $this->doctrine->em->getRepository('Entity\User');

		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$this->load->helper('angular');

			$_POST = getPostData();

			extract($_POST);
			try {
				$user = $userRepository->authenticateUser($userName,$password);

				if($user){

					$bannedStatus = $userRepository->checkBannedStatus($user->getEmail());
					if($bannedStatus){
						session_start();
						$_SESSION['id'] = $user->getId();
						$_SESSION['email'] = $user->getEmail();
						$_SESSION['userName'] = $user->getUserName();

						$response->setSuccess(true);
						$response->setData(array('msg'=>'Logged In'));
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User Banned'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'User does not exist'));
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

		$response->respond();
		die();
	}

	function fbAuthenticate(){

		$userRepository = $this->doctrine->em->getRepository('Entity\User');
		$response = new Response();
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			extract($_POST);
			try {
				$user = $userRepository->checkUser($email);
				if($user){
					if($user->getIsVerified() == 1){
						$bannedStatus = $userRepository->checkBannedStatus($user->getEmail());
						if($bannedStatus){
							$user->setProfilePicturePath($_POST['picture']);

							$this->doctrine->em->persist($user);
							$this->doctrine->em->flush();

							session_start();
							$_SESSION['id'] = $user->getId();
							$_SESSION['email'] = $user->getEmail();
							$_SESSION['userName'] = $user->getUserName();

							$response->setSuccess(true);
							$response->setData(array(
								'code'=>1502,
								'msg'=>'Logged In',
								'id'=>$user->getId()
							));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setError(array('msg'=>'User Banned'));
						}
					}else{
						$$response->setSuccess(false);
						$response->setData(array('code'=>1500,
							'msg'=>'User not verified'));
						$response->setError('');
					}
				}else{

					$user = new User();
					$user->setEmail($email);
					$user->setUserName($email);
					$user->setGender($gender);

					$this->load->helper ( 'string' );
					$password = random_string ( 'alnum' );

					$user->setPassword(md5($password));
					$user->setCredibilityPoints(0);
					$user->setIsbannedStatus(0);
					$user->setProfilePicturePath($_POST['picture']);
					$user->setIsVerified(1);
					$user->setHasSeen(0);
					
					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();

					session_start();
					$_SESSION['id'] = $user->getId();
					$_SESSION['email'] = $user->getEmail();
					$_SESSION['userName'] = $user->getUserName();

					// $userVerification = new UserVerification();
					// $userVerification->setUser($user);
					// $timeStamp = time();
					// $verificationLink = md5($timeStamp);
					// $userVerification->setVerificationLink($verificationLink);
					// $userVerification->setType('REGISTERVERIFY');

					// $this->doctrine->em->persist($userVerification);
					// $this->doctrine->em->flush();


					// $mail = new PHPMailer ();
				
					// $mail->isSMTP (); // Set mailer to use SMTP
					// $mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
					// $mail->Port = 465;
					// $mail->SMTPAuth = true; // Enable SMTP authentication
					// $mail->Username = 'varun1505@gmail.com'; // SMTP username
					// $mail->Password = 'IjpjcAULZL_o_bpT0KEgqA'; // SMTP password
					// $mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
					
					// $mail->From = 'admin@mojoscribe.com';
					// $mail->FromName = 'Mojo-Scribe';
					// $mail->addAddress ( $_POST ['email'] ); // Add a recipient
					
					// $mail->WordWrap = 50; // Set word wrap to 50 characters
					// $mail->isHTML ( true ); // Set email format to HTML
					
					// $mail->Subject = "Registration at Mojo-Scribe";
					// $mail->Body = "You have been successfully registered at Mojo-Scribe."."<br>";
					// $mail->Body .= "To verify your account please follow the link below:<br><br>";
					// $mail->Body .= "Verification Link: " .base_url()."verify?id=".$verificationLink."<br><br><br>";
					// $mail->Body .= "This is an auto-generated email. Please do not reply to this e-mail";
					
					// $mail->send ();

					$response->setSuccess(true);
					$response->setData(array(
						'code'=>1501,
						'msg'=>'User Registered',
						'data'=>$_SESSION['id']
					));
					$response->setError('');
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setError(array('msg'=>'Oops! Something went wrong'));
			}
		}else{
			redirect('/');
		}
		$response->respond();
		die();
	}


	function gPlusAuthenticate(){
		$userRepository = $this->doctrine->em->getRepository('Entity\User');
		$response = new Response();
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			extract($_POST);
			// echo "<pre>";
			// print_r($_POST);
			// die();
			try {
				$user = $userRepository->checkUser($emails[0]['value']);
				if($user){
					$bannedStatus = $userRepository->checkBannedStatus($user->getEmail());
					if($bannedStatus){

						session_start();
						$_SESSION['id'] = $user->getId();
						$_SESSION['email'] = $user->getEmail();
						$_SESSION['userName'] = $user->getUserName();

						$response->setSuccess(true);
						$response->setData(array(
							'code'=>1502,
							'msg'=>'Logged In'
						));
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setError(array('msg'=>'User Banned'));
					}
				}else{
					$user = new User();
					$user->setEmail($emails['0']['value']);

					// $user->setUserName($displayName);
					$picture = substr($image['url'], 0,-2);

					$pictureUrl = $picture."250";

					$user->setProfilePicturePath($pictureUrl);
					$user->setUserName($emails['0']['value']);

					$user->setProfilePicturePath($image['url']);
					$user->setGender($gender);
					$user->setCredibilityPoints(0);
					$user->setIsbannedStatus(0);

					$this->load->helper ( 'string' );
					$password = random_string ( 'alnum' );

					$user->setPassword(md5($password));
					$user->setCredibilityPoints(0);
					$user->setIsbannedStatus(0);
					$user->setIsVerified(1);
					$user->setHasSeen(0);
					
					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();


					// $userVerification = new UserVerification();
					// $userVerification->setUser($user);
					// $timeStamp = time();
					// $verificationLink = md5($timeStamp);
					// $userVerification->setVerificationLink($verificationLink);
					// $userVerification->setType('REGISTERVERIFY');

					// $this->doctrine->em->persist($userVerification);
					// $this->doctrine->em->flush();

					// $mail = new PHPMailer ();
				
					// $mail->isSMTP (); // Set mailer to use SMTP
					// $mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
					// $mail->Port = 465;
					// $mail->SMTPAuth = true; // Enable SMTP authentication
					// $mail->Username = 'varun1505@gmail.com'; // SMTP username
					// $mail->Password = 'IjpjcAULZL_o_bpT0KEgqA'; // SMTP password
					// $mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
					
					// $mail->From = 'admin@mojoscribe.com';
					// $mail->FromName = 'Mojo-Scribe';
					// $mail->addAddress ( $_POST ['email'] ); // Add a recipient
					
					// $mail->WordWrap = 50; // Set word wrap to 50 characters
					// $mail->isHTML ( true ); // Set email format to HTML
					
					// $mail->Subject = "Registration at Mojo-Scribe";
					// $mail->Body = "You have been successfully registered at Mojo-Scribe."."<br>";
					// $mail->Body .= "To verify your account please follow the link below:<br><br>";
					// $mail->Body .= "Verification Link: " .base_url()."verify?id=".$verificationLink."<br><br><br>";
					// $mail->Body .= "This is an auto-generated email. Please do not reply to this e-mail";
					
					// $mail->send ();

					session_start();
					$_SESSION['id'] = $user->getId();
					$_SESSION['email'] = $user->getEmail();
					$_SESSION['userName'] = $user->getUserName();

					$response->setSuccess(true);
					$response->setData(array(
						'code'=>1501,
						'msg'=>'User Registered',
						'data'=>$_SESSION['id']
					));
					$response->setError('');
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setError(array('msg'=>'Oops! Something went wrong'));
			}
		}else{
			redirect('/');
		}
		$response->respond();
		die();
	}

	function checkEmail(){
		$response = new Response();
		// if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "POST"){
					$_POST = file_get_contents("php://input");
					$_POST = json_decode($_POST);
					
					$userEmail = $this->userRepository->checkUser($_POST);

					if(false != $userEmail){

						if(1 == $userEmail->getIsVerified()){

							$response->setSuccess(true);
							$response->setData(array('msg'=>'Email Exists'));
							$response->setError('');
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array(
								'code'=>'1003',
								'msg'=>'User not verified'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array(
							'code'=>'1004',
							'msg'=>'Email does not exist'));
					}
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'1100',
						'msg'=>'Method Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));
			}
		// }else{
		// 	$response->setSuccess(false);
		// 	$response->setData('');
		// 	$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		// }
		$response->respond();
		die();
	}

	
	function logout(){
		session_start();
		session_destroy();
		redirect('/');
	}

}
