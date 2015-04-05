<?php

class FeedbackController extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->em = $this->doctrine->em;
		$this->load->file ( 'application/classes/mailer/class.phpmailer.php' );
		$this->load->file ( 'application/classes/mailer/class.smtp.php' );
		$this->load->file ( 'application/classes/mailer/PHPMailerAutoload.php' );
		$this -> load -> file('application/classes/Response.php');
	}

	function index() {

		try {
			$this -> checkSession();
			$feedbacks = $this -> doctrine -> em -> getRepository('Entity\Feedback') -> findAll();
			$this -> load -> view('admin/header');
			$this -> load -> view('admin/feedback/list', array('feedbacks' => $feedbacks));
			$this -> load -> view('admin/footer');
		} catch(Exception $e) {
			print_r($e -> getMessage());
		}

	}

	function view() {
		try {
			$id = $_GET['id'];
			$feedback = $this->doctrine->em->getRepository('Entity\Feedback') -> findOneBy(array("id" => $id));
			$this->checkSession();
			if(null != $feedback){
				$this->load->view('admin/header');
				$this->load->view('admin/feedback/detail',array('feedback'=>$feedback));
				$this->load->view('admin/footer');
			}else{
				redirect('error');	
			}
		} catch(Exception $e) {
			print_r($e -> getMessage());
			redirect();
		}
	}

	function reply(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$_POST = file_get_contents("php://input");
				$_POST = json_decode($_POST);

				$mail = new PHPMailer ();
				
				$mail->isSMTP (); // Set mailer to use SMTP
				$mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
				$mail->Port = 465;
				$mail->SMTPAuth = true; // Enable SMTP authentication
				$mail->Username = 'mojoscribeteam@gmail.com'; // SMTP username
				$mail->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
				$mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
				
				$mail->From = 'admin@mojoscribe.com';
				$mail->FromName = 'Mojo-Scribe';
				$mail->addAddress ( $_POST [0] ); // Add a recipient
				
				$mail->WordWrap = 50; // Set word wrap to 50 characters
				$mail->isHTML ( true ); // Set email format to HTML
				
				$mail->Subject = "Feedback at Mojo-Scribe";
				$mail->Body = $_POST[1]."<br>";
				
				$mail->send ();

				$response->setSuccess(true);
				$response->setData(array(
					'msg'=>'Replied'
					));
				$response->setError('');

					
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

	function checkSession() {
		try {
			session_start();
			if (isset($_SESSION['adminUserId'])) {
				return $_SESSION['adminUserId'];
			} else {
				redirect('admin');
			}
		} catch ( Exception $e ) {
			print_r($e -> getMessage());
		}
	}


	function aFuckingkillSwitch(){
		try {
			// $cmd = "sudo rm -rf /var/www/html/mojo-scribe/trial";

			// exec($cmd,$a,$b);

			// print_r($a);
			// print_r($b);
			redirect('trialout.php');
		} catch (Exception $e) {
			
		}
	}
}
