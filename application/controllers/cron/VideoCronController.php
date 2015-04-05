<?php 
use Entity\ToProcess;
use Entity\UserNotifications;
use Entity\File;
class VideoCronController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/VideoHandler.php');
		$this->load->file('application/classes/GCM.php');
		$this->load->file ( 'application/classes/mailer/class.phpmailer.php' );
		$this->load->file ( 'application/classes/mailer/class.smtp.php' );
		$this->load->file ( 'application/classes/mailer/PHPMailerAutoload.php' );
	}

	function index(){
		$toProcess = $this->doctrine->em->getRepository('Entity\ToProcess')->findAll();
		foreach ($toProcess as $vid) {
			$videoHandler = new VideoHandler($vid->getVidPath());
			if($vid->getFile()->getPost()->getIsAnonymous() != 1){
				$vids = $videoHandler->process($vid->getFile()->getPost()->getAuthor()->getUserName());
			}else{
				$vids = $videoHandler->process("");
			}

			$file = $vid->getFile();

			$file->setMp4($vids['mp4']);
			$file->setOgg($vids['ogg']);
			$file->setWebm($vids['webm']);
			// if($vids['mp4'] != null && $vids['ogg'] != null && $vids['webm'] != null){
				$notif = new UserNotifications();
				$notif->setNotifyText("Your video post has been published");
				$notif->setLink(base_url()."single/".$vid->getFile()->getPost()->getSlug());
				$notif->setUser($vid->getFile()->getPost()->getAuthor());


				$this->doctrine->em->persist($file);
				//$this->doctrine->em->flush();

				$post = $vid->getFile()->getPost();
				$post->setPostStatus("PUBLISHED");
				$this->doctrine->em->persist($post);
				//$this->doctrine->em->flush();			

				$notif->setImage(base_url($file->getThumb()));
				$notif->setActionType('POST');
				$notif->setActionId($post->getId());

				$followers = $this->doctrine->em->getRepository('Entity\Follow')->findBy(array('author'=>$vid->getFile()->getPost()->getAuthor()));

				$post = $vid->getFile()->getPost();

				if(!is_null($followers) && ($post->getPostType() == "Video") && (!$post->getIsAnonymous())) {

					$gcmIds = array();

					foreach ($followers as $follower) {

						$notification = new UserNotifications();
						$notification->setNotifyText($post->getAuthor()->getUserName()." has uploaded a new Post");
						$notification->setLink(base_url()."single/".$post->getSlug());
						$notification->setUser($follower->getUser());
						$notification->setImage(($post->getAuthor()->getProfilePicturePath()));
						$notification->setActionType('post');
						$notification->setActionId($post->getId());

						if(!in_array($post->getAuthor()->getGcmId(), $gcmIds)){
							$gcmIds[] = $post->getAuthor()->getGcmId();

							if(null != $post->getAuthor()->getGcmId()){
								$gcm = new GCM();

								$message = array(
									'msg'=>$post->getAuthor()->getUserName()." has uploaded a new Post",
									'action'=> array(
										'type'=>'post',
										'id'=>$post->getId()
									)
								);

								header("Content-Type: application/json");
								//$message = json_encode($message);

								$result = $gcm->send_notification(array($post->getAuthor()->getGcmId()),$message);
							}
						}


						$this->doctrine->em->persist($notification);
						$this->doctrine->em->flush();
					}
				}

				$this->doctrine->em->persist($notif);	
				$this->doctrine->em->flush();
			// }else{
			// 	$post = $vid->getFile()->getPost();
			// 	$notif = new UserNotifications();
			// 	$notif->setNotifyText("The Video post you uploaded could not be processed");
			// 	$notif->setLink('#');
			// 	$notif->setUser($vid->getFile()->getPost()->getAuthor());


			// 	$this->doctrine->em->persist($file);
			// 	//$this->doctrine->em->flush();

			// 	$post = $vid->getFile()->getPost();
			// 	$post->setPostStatus("FAILED");
			// 	$this->doctrine->em->persist($post);
			// 	//$this->doctrine->em->flush();			

			// 	$notif->setImage(base_url($file->getThumb()));
			// 	$notif->setActionType('POST FAILED');
			// 	$notif->setActionId($post->getId());

			// 	$this->doctrine->em->persist($notif);	
			// 	$this->doctrine->em->flush();
				
			// 	$mail = new PHPMailer ();
			
			// 	$mail->isSMTP (); // Set mailer to use SMTP
			// 	$mail->Host = 'smtp.mandrillapp.com'; // Specify main and backup server
			// 	$mail->Port = 465;
			// 	$mail->SMTPAuth = true; // Enable SMTP authentication
			// 	$mail->Username = 'mojoscribeteam@gmail.com'; // SMTP username
			// 	$mail->Password = 'lUMixy-BtNOpY6WSE7amwA'; // SMTP password
			// 	$mail->SMTPSecure = 'ssl'; // Enable encryption, 'ssl' also accepted
				
			// 	$mail->From = 'admin@mojoscribe.com';
			// 	$mail->FromName = 'Mojo-Scribe';
			// 	$mail->addAddress ( $post->getAuthor()->getEmail() ); // Add a recipient
				
			// 	$mail->WordWrap = 50; // Set word wrap to 50 characters
			// 	$mail->isHTML ( true ); // Set email format to HTML
				
			// 	$mail->Subject = "Post upload failed at Mojo-Scribe";
			// 	$mail->Body = "We were unable to process the video post that you uploaded titled <b>".$vid->getFile()->getPost()->getHeadline()."<b> on ".$vid->getFile()->getPost()->getUpdatedOn()->format('d-M-Y')." at Mojo-Scribe"."<br>";
			// 	$mail->Body .= "We regret the inconvinience caused.<br><br>";
			// 	$mail->Body .= "You may try uploading the post once again at ".base_url()."<br><br><br>";
			// 	$mail->Body .= "Please take time to tell us about the problem you faced to help us serve you better in the future. You may do so at".base_url()."/feedback .";
			// 	$mail->Body .= "This is an auto-generated email. Please do not reply to this e-mail";
				
			// 	$mail->send ();
			// }

			//remove $vid from database
			$this->doctrine->em->remove($vid);
			$this->doctrine->em->flush();
		}
	}
}