<?php 
use Entity\UserNotifications;
class FeaturedPostsController extends CI_Controller {
 	var $postRepository;
 	function __construct(){
 		parent::__construct();
 		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
 		$this->load->file('application/classes/GCM.php');
 		$this->load->file('application/classes/Response.php');
 	}
 
 	function index(){
 		$this->checkSession ();

 		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isFeatured'=>1));

 		$data = array();
		foreach ($posts as $post) {
			$temp['id'] = $post->getId();
			$temp['title'] = $post->getHeadline();
			$temp['type'] = $post->getPostType();
			$temp['date'] = $post->getUpdatedOn()->format('d-M-Y');
			$temp['slug'] = $post->getSlug();

			if(null != $post->getPostDetails()){
				if(null != $post->getPostDetails()->getNumberOfViews()){
					$temp['views'] = $post->getPostDetails()->getNumberOfViews();
				}else{
					$temp['views'] = 0;
				}

				if(null != $post->getPostDetails()->getRating()){
					$temp['rating'] = $post->getPostDetails()->getRating();
				}else{
					$temp['rating'] = 0;
				}

				if(null != $post->getPostDetails()->getNumberOfShares()){
					$temp['shares'] = $post->getPostDetails()->getNumberOfShares();
				}else{
					$temp['shares'] = 0;
				}

			}else{
				$temp['views'] = 0;
				$temp['shares'] = 0;
				$temp['rating'] = 0;
			}

			$flags = $this->doctrine->em->getRepository('Entity\FlagLog')->findBy(array('post'=>$post));

			if(null != $flags){
				$temp['flags'] = count($flags);
			}else{
				$temp['flags'] = 0;
			}

			$data[] = $temp;
		}

 		if(!is_null($posts)){
	 		$this->load->view('admin/header');
	 		$this->load->view('admin/featured/list',array('featured'=>$data));
	 		$this->load->view('admin/footer',array('tableId'=>'featured'));
 		}
 	}

 	function remove(){
 		$this->checkSession();

 		$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_GET['id']));

 		if(!is_null($post)){
 			$post->setIsFeatured(0);
 			$this->doctrine->em->persist($post);
 			$this->doctrine->em->flush();
 		
 			redirect('admin/featured?removed');
 		}else{
 			redirect('admin/featured');
 		}
 	}

 	function makeFeatured(){
 		$response = new Response();
 		try {
 			$this->checkSession();

 			if(isset($_SESSION['adminUserId'])){
 				$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_GET['id']));
 				$result = "";
 				if(!is_null($post)){
 					$post->setIsFeatured(1);
 					
					$notification = new UserNotifications();
					$notification->setNotifyText("Your Post ".$post->getHeadline()." has been made a  featured post.");
					$notification->setLink(base_url()."single/".$post->getSlug());
					$notification->setUser($post->getAuthor());
					$files = $post->getFiles();
					$notification->setImage(base_url().$files[0]->getThumb());
					$notification->setActionType('post');
					$notification->setActionId($post->getId());

					if(null != $post->getAuthor()->getGcmId()){
						$gcm = new GCM();

						$message = array(
							'msg'=>"Your Post".$post->getHeadline()." has been made a featured post.",
							'action'=> array(
								'type'=>'post',
								'id'=>$post->getId()
							)
						);

						header("Content-Type: application/json");
						//$message = json_encode($message);

						$result = $gcm->send_notification(array($post->getAuthor()->getGcmId()),$message);
						
					}

					$this->doctrine->em->persist($notification);
 					$this->doctrine->em->persist($post);
 					$this->doctrine->em->flush();

 					$response->setSuccess(true);
 					$response->setData(array('msg'=>$result));
 					$response->setError('');
 				}else{
 					$response->setSuccess(false);
 					$response->setData('');
 					$response->setError(array('msg'=>'Post not found'));
 				}
 			}else{
 				$response->setSuccess(false);
 				$response->setData('');
 				$response->setError(array(
					'msg'=>'User not admin' 					
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
			session_start ();
			if (isset ( $_SESSION ['adminUserId'] )) {
				return $_SESSION ['adminUserId'];
			} else {
				redirect ( 'admin' );
			}
		} catch ( Exception $e ) {
			redirect ( 'error' );
		}
	}

	public function sendNotif(){
		$gcm = new GCM();
		$message = array(
				'msg'=>"Your Post has been made a featured post.",
				'action'=> array(
					'type'=>'post',
					'id'=> 2
				)
			);
		
		$result = $gcm->send_notification(array("APA91bFtC1X1z81aawaqdW7KSMv2y20zNvzD6kbcJ1WiqvBBwL_HJ1xVWIzp0X0GQaPp5zYoYK5H4U8Z3BoLaOm-nhue3wz9j00YuCenPFdkwfA3Xqk3OFVv7RYTG_F41cG3H2Np9PmkPu_IgGxyqtn5eNy71MeRB_eDOrHgejc9d1aV-ObneBA"),$message);
	}

 } ?>