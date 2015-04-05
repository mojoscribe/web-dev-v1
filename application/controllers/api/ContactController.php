<?php 
class ContactController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->load->file ('application/classes/mailer/class.phpmailer.php');
		$this->load->file ('application/classes/mailer/class.smtp.php');
		$this->load->file ('application/classes/mailer/PHPMailerAutoload.php');
		$this->load->helper('api');
 	}
 
 	function index(){
 		$response = new Response();
 		
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
		 			if($_SERVER['REQUEST_METHOD'] == "POST"){
		 				extract($_POST);

		 				$mail = new PHPMailer ();
			
						$mail->isSMTP (); // Set mailer to use SMTP
						$mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
						$mail->Port = 465;
						$mail->SMTPAuth = true; // Enable SMTP authentication
						$mail->Username = 'mojoscribeteam@gmail.com'; // SMTP username
						$mail->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
						$mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
						
						$mail->From = $_POST['email'];
						$mail->FromName = $_POST['name'];
						$mail->addAddress ( "sohamkhare91@gmail.com" ); // Add a recipient
						
						$mail->WordWrap = 50; // Set word wrap to 50 characters
						$mail->isHTML ( true ); // Set email format to HTML
						
						$mail->Subject = "Contact at Mojo-Scribe";
						$mail->Body = $_POST['message'];
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
 } ?>