<?php
use Entity\User;
use Entity\UserVerification;
class RegisterCOntroller extends CI_Controller {
	var $userRepository;
	function __construct(){
		parent::__construct();
		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
		$this->load->file ( 'application/classes/mailer/class.phpmailer.php' );
		$this->load->file ( 'application/classes/mailer/class.smtp.php' );
		$this->load->file ( 'application/classes/mailer/PHPMailerAutoload.php' );
		$this->load->file ('application/classes/Response.php');
		$this->load->helper('angular');
	}

	public function index(){
		$response = new Response();
		if(false != checkCsrf()){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$this->load->helper('angular');
				$_POST = getPostData();

				extract($_POST);
				try {
					$existingUser = $this->userRepository->checkUser($_POST['email']);

					if(false != $existingUser){
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User already registered'));
					}else{

						$user = new User();
						$user->setEmail($email);
						$user->setUserName($userName);

						$user->setPassword(md5($password));
						$user->setCredibilityPoints(0);
						$user->setIsbannedStatus(0);
						$user->setHasSeen(0);

						$this->doctrine->em->persist($user);

						$userVerification = new UserVerification();
						$userVerification->setUser($user);
						$timeStamp = time();
						$verificationLink = md5($timeStamp);
						$userVerification->setVerificationLink($verificationLink);
						$userVerification->setType('REGISTERVERIFY');

						$this->doctrine->em->persist($userVerification);
						$this->doctrine->em->flush();

						$mail = new PHPMailer ();
					
						$mail->isSMTP (); // Set mailer to use SMTP
						$mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
						$mail->Port = 465;
						$mail->SMTPAuth = true; // Enable SMTP authentication
						$mail->Username = 'mojoscribeteam@gmail.com'; // SMTP username
						$mail->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
						$mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
						
						$mail->From = 'admin@mojoscribe.com';
						$mail->FromName = 'MojoScribe';
						$mail->addAddress ( $_POST ['email'] ); // Add a recipient
						
						$mail->WordWrap = 50; // Set word wrap to 50 characters
						$mail->isHTML ( true ); // Set email format to HTML
						
						$mail->Subject = "Registration at MojoScribe";
						$mail->Body = "You have been successfully registered at MojoScribe."."<br>";
						$mail->Body .= "To verify your account please follow the link below:<br><br>";
						$mail->Body .= "Verification Link: " .base_url()."verify?id=".$verificationLink."<br><br><br>";
						$mail->Body .= "This is an auto-generated email. Please do not reply to this e-mail";
						
						$mail->send ();


						$response->setSuccess(true);
						$response->setData(array('msg'=>'Registered'));
						$response->setError('');
					}
				} catch (Exception $e) {
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Something went wrong'));
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

	function firstTime(){
		try {
			if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['userName']) && isset($_GET['email'])) {
				$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('userName'=>$_GET['userName'],'email'=>$_GET['email']));
				if(null != $user){
					$data = array();

					$data['id'] = $user->getId();
					$data['userName'] = $user->getUserName();
					$data['email'] = $user->getEmail();

					$this->load->view('register-header');
					$this->load->view('firstTime',array('userData'=>$data));
					$this->load->view('footer',array('scripts'=>array('controllers/firstTimePagecontroller.js')));
				}else{
					/*echo $e->getMessage();
					die();*/
					redirect('/?usernotexist');
				}
			}else{
				/*echo $e->getMessage();
				die();*/
				redirect('/');
			}
		} catch (Exception $e) {
			/*echo $e->getMessage();
			die();*/
			redirect('technicalProblem');
		}
	}

	function changeEmail(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['userId'])) {
					$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_GET['userId']));

					if(null != $user){
						if($_GET['email'] != $user->getEmail()){

							$userVerificationOld = $this->doctrine->em->getRepository('Entity\UserVerification')->findOneBy(array('user'=>$user));

							if(null != $userVerificationOld){
								$this->doctrine->em->remove($userVerificationOld);

								$user->setEmail($_GET['email']);
								$this->doctrine->em->persist($user);
							
								$userVerificationNew = new UserVerification();
								$userVerificationNew->setUser($user);
								$timeStamp = time();
								$verificationLink = md5($timeStamp);
								$userVerificationNew->setVerificationLink($verificationLink);
								$userVerificationNew->setType('REGISTERVERIFY');

								$this->doctrine->em->persist($userVerificationNew);
								$this->doctrine->em->flush();

								$mail = new PHPMailer ();
						
								$mail->isSMTP (); // Set mailer to use SMTP
								$mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
								$mail->Port = 465;
								$mail->SMTPAuth = true; // Enable SMTP authentication
								$mail->Username = 'mojoscribeteam@gmail.com'; // SMTP username
								$mail->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
								$mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
								
								$mail->From = 'admin@mojoscribe.com';
								$mail->FromName = 'MojoScribe';
								$mail->addAddress ( $_GET ['email'] ); // Add a recipient
								
								$mail->WordWrap = 50; // Set word wrap to 50 characters
								$mail->isHTML ( true ); // Set email format to HTML
								
								$mail->Subject = "Email change at MojoScribe";
								$mail->Body = "Your email was changed at MojoScribe."."<br>";
								$mail->Body .= "To verify your account please follow the link below:<br><br>";
								$mail->Body .= "Verification Link: " .base_url()."verify?id=".$verificationLink."<br><br><br>";
								$mail->Body .= "This is an auto-generated email. Please do not reply to this e-mail";
								
								$mail->send ();

								$response->setSuccess(true);
								$response->setData(array('msg'=>'Registered'));
								$response->setError('');
							}else{
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array('msg'=>'Verification error'));
							}
						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'Email same'));
						}
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User not found'));
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

	public function verifyUser(){
		try {
			
			if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])){

				$userVerification = $this->doctrine->em->getRepository('Entity\UserVerification')->findOneBy(array('verificationLink'=>$_GET['id'] , 'type'=>'REGISTERVERIFY'));
				if(null != $userVerification){

					$user = $userVerification->getUser();

					$user->setIsVerified(1);

					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();

					session_start();
					$_SESSION['id'] = $user->getId();
					$_SESSION['email'] = $user->getEmail();
					$_SESSION['userName'] = $user->getUserName();

					redirect('settings/profile?userId='.$user->getId());

				}else{
					redirect('');
				}
			}else if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['email'])){
				$this->forgotPassVerify();
			}else{
				redirect('/');
			}
		} catch (Exception $e) {
			// echo $e->getMessage();
			// die();
			redirect('technicalProblem');			
		}
	}

	public function changePass(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			extract($_POST);
			$userRepository = $this->doctrine->em->getRepository('Entity\User');
			session_start ();
			if (! isset ( $_SESSION ['id'] )) {
				redirect ( 'login?sessionexpired' );
			} else {
				try {
					$id = $_SESSION ['id'];
					$user = $userRepository->checkUser($email);
					if ($user) {
						$password = $_POST ['newpassword'];
						
						$user->setPassword ( md5 ( $password ) );
						$this->doctrine->em->persist ( $user );
						$this->doctrine->em->flush ();
						
						redirect ( 'login?passwordchange=success' );
					} else {
						redirect ( 'login?nouser' );
					}
				} catch ( Exception $e ) {
					// $e->getMessage ();
					redirect('technicalProblem');
				}
			}
		}else{
			redirect('login');
		}
	}

	function forgotPassInit(){
		try {
			if(false != isUserLoggedIn()){
				redirect('/');
			}else{
				$this->load->view('header');
				$this->load->view('forgotPass');
				$this->load->view('footer', array('scripts'=>array('controllers/forgotPasscontroller.js')));
			}
		} catch (Exception $e) {
			// echo "<pre>";
			// print_r($e->getMessage());
			// die();
			redirect('technicalProblem');
		}
	}

	function forgotPassEmail(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$_POST = file_get_contents("php://input");
				$_POST = json_decode($_POST);

				$user = $this->userRepository->checkUser($_POST);
			
				if(false != $user){

					$userVerificationOld = $this->doctrine->em->getRepository('Entity\UserVerification')->findOneBy(array('user'=>$user , 'type'=>'FORGOTPASS'));

					if(null != $userVerificationOld){
						$this->doctrine->em->remove($userVerificationOld);
						$this->doctrine->em->flush();
					}

					$userVerificationNew = new UserVerification();
					$userVerificationNew->setUser($user);
					$timeStamp = time();
					$verificationLink = md5($timeStamp);
					$userVerificationNew->setVerificationLink($verificationLink);
					$userVerificationNew->setType('FORGOTPASS');

					$this->doctrine->em->persist($userVerificationNew);
					$this->doctrine->em->flush();

					$mail = new PHPMailer ();
			
					$mail->isSMTP (); // Set mailer to use SMTP
					$mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
					$mail->Port = 465;
					$mail->SMTPAuth = true; // Enable SMTP authentication
					$mail->Username = 'mojoscribeteam@gmail.com'; // SMTP username
					$mail->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
					$mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
					
					$mail->From = 'admin@mojoscribe.com';
					$mail->FromName = 'MojoScribe';
					$mail->addAddress ( $_POST); // Add a recipient
					
					$mail->WordWrap = 50; // Set word wrap to 50 characters
					$mail->isHTML ( true ); // Set email format to HTML
					
					$mail->Subject = "Forgot Password/Change Password at MojoScribe";
					$mail->Body = " A link has been generated for forgot Password at MojoScribe."."<br>";
					$mail->Body .= "To change your password please follow the link below:<br><br>";
					$mail->Body .= "Forgot Password Link: " .base_url()."verify?email=".$verificationLink."<br><br><br>";
					$mail->Body .= "This is an auto-generated email. Please do not reply to this e-mail";
					
					$mail->send ();

					$response->setSuccess(true);
					$response->setData(array('msg'=>'Sent'));
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'2002',
						'msg'=>'User does not exist'
						));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>'1100',
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

	function forgotPassVerify(){
		try {
			
			if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['email'])){
				$userVerification = $this->doctrine->em->getRepository('Entity\UserVerification')->findOneBy(array('verificationLink'=>$_GET['email'] , 'type'=>'FORGOTPASS'));

				if(null != $userVerification){
					$user = $userVerification->getUser();

					redirect('forgotPass?verify=success');
				}else{
					redirect('/');
				}
			}else{
				redirect('/');
			}
		} catch (Exception $e) {
			// echo "<pre>";
			// print_r($e->getMessage());
			// die();
			redirect('technicalProblem');
		}
	}


	function forgotPassword() {
		$response = new Response();
		$userRepository = $this->doctrine->em->getRepository('Entity\User');
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			try {
				$user = $userRepository->checkUser($_POST[1]);
				if (false != $user) {
					$user->setPassword(md5($_POST[0]));
					$this->doctrine->em->persist($user);
					$this->doctrine->em->flush();

					$response->setSuccess(true);
					$response->setData(array(
						'code'=>'',
						'msg'=>'Password Reset'
						));
					$response->setError('');
											
				} else {
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'code'=>'',
						'msg'=>'User was not found'
						));
				}
			} catch ( Exception $e ) {
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
				'code'=>'1100',
				'msg'=>'Method Error'
				));
		}
		$response->respond();
		die();
	}

	function checkUserName(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
			
				if($_SERVER['REQUEST_METHOD'] == "POST"){
					
					$_POST = file_get_contents("php://input");
					$_POST = json_decode($_POST);
					$_POST = get_object_vars($_POST);

					$userName = $this->userRepository->checkUserName($_POST);

					if(false != $userName){
						
						$invalidUserNames = unserialize(INVALIDUSERNAMES);

						foreach ($invalidUserNames as $name) {
							if($_POST == $name){
								$response->setSuccess(false);
								$response->setData('');
								$response->setError(array(
									'code'=>'',
									'msg'=>'The User Name you are trying to enter is Invalid'
								));

								$response->respond();
								die();
							}
						}

						$response->setSuccess(true);
						$response->setData(array('msg'=>'User Name available'));
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'User Name already exists. Please try another one'));
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

	function checkEmail(){
		$response = new Response();
		
		if(false != checkCsrf()){

			if($_SERVER['REQUEST_METHOD'] == "POST"){
				
				$_POST = file_get_contents("php://input");

				try {
					$userEmail = $this->userRepository->checkUser($_POST);

					if(true != $userEmail){
						$response->setSuccess(true);
						$response->setData(array('msg'=>'Email available'));
						$response->setError('');
					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'Email Address already exists'));
					}
				} catch (Exception $e) {
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Something went wrong'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Method Error'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}

		$response->respond();
		die();
	}

	
}