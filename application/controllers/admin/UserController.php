<?php
use Entity\UserNotifications;
class UserController extends CI_Controller
{
function __construct() {
		parent::__construct ();
		$this->em = $this->doctrine->em;
		$this->load->file ( 'application/classes/Response.php' );
		$this->load->file('application/classes/GCM.php');
	}
	function index() {
	
	try{
	$this->checkSession();
	$users = $this->doctrine->em->getRepository('Entity\User')->findAll();
	$this->load->view('admin/header');
	$this->load->view('admin/user/list',array('users'=>$users));
	$this->load->view('admin/footer');
	
	
	
	}catch(Exception $e)
	  {
	  print_r($e->getMessage());
	  }
	
	}
	
	function ban()
	{
	   try
	   {
	      $id = $_GET['id'];
	      $user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array("id"=>$id));
	      if( $user->getIsBannedStatus() == 0)
	      {$user->setIsBannedStatus(1);}
	      else
	      { $user->setIsBannedStatus(0);}
	      $this->doctrine->em->persist($user);
	      $this->doctrine->em->flush();
	      redirect('/admin/users');
	   }
	   catch(Exception $e)
	   {
	      print_r($e->getMessage());
	      redirect();
	   }
	}

	function warn(){
		try {

			$this->checkSession();

			$user = $this->doctrine->em->getRepository('Entity\User')->findOneBy(array('id'=>$_GET['id']));

 			if(!is_null($user)){
 				
 				$userNotifications = new UserNotifications();

 				$userNotifications->setUser($user);
 				$userNotifications->setNotifyText("You have been warned by the Admin");
 				$userNotifications->setImage(base_url()."assets/images/admin.png");
 				$userNotifications->setLink(base_url().$user->getUserName());
 				$userNotifications->setActionType('user');
				$userNotifications->setActionId($user->getId());

				if(null != $user->getGcmId() || "" != $user->getGcmId()){
					$gcm = new GCM();

					$message = array(
						'msg'=>"You have been warned by the Admin",
						'action'=>array(
							'type'=>'admin',
							'id'=>$user->getId()
						)
					);

					header("Content-Type: application/json");
					$message = json_encode($message);

					$result = $gcm->send_notifications($user->getGcmId(),$message);
					
				}

 				$this->doctrine->em->persist($userNotifications);
 				$this->doctrine->em->flush();

 				redirect('admin/users?success');
 				
 			}else{
 				redirect('admin/users?usererror');
 			}
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}
	
	
	function checkSession() {
		try {
			session_start ();
			if (isset ( $_SESSION ['adminUserId'] )) {
				return $_SESSION ['adminUserId'];
			} else {
				redirect ( 'admin' );
			}
		} catch ( Exception $e ) {
			print_r($e->getMessage());
		}
	}
}