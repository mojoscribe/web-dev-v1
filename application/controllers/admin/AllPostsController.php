<?php 
use Entity\Post;
use Entity\UserNotifications;
class AllPostsController extends CI_Controller {
	var $postRepository;
	function __construct(){
		parent::__construct();
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->load->file('application/classes/Response.php');
		$this->load->file('application/classes/GCM.php');
	}

	function index(){
		
		$this->checkSession();

		try {
			
			$this->load->view('admin/header');
			$this->load->view('admin/allPosts/list');
			$this->load->view('admin/footer',array('tableId'=>'anonymous','scripts'=>array('js/controllers/admin/allPostsController.js')));

		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
			// redirect('error');
		}
	}

	function getAllPosts(){
		$response = new Response();

		$this->checkSession();

		$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('postStatus'=>array('PUBLISHED','UNPUBLISHED','REMOVED')),array('id'=>'desc'));

		if($posts){
			$data = array();
			$i = 1;
			foreach ($posts as $post) {
				$temp['id'] = $post->getId();
				$temp['serial'] = $i++;
				$temp['title'] = $post->getHeadline();

				if($post->getIsAnonymous() == true){
					$temp['author'] = $post->getAuthor()->getUserName()."(Anonymous)";
				}else{
					$temp['author'] = $post->getAuthor()->getUserName();
				}

				$date = $post->getUpdatedOn();
				$date = $date->format('d-M-Y');
				$temp['date'] = $date;
				$temp['mediaType'] = $post->getPostType();
				$temp['slug'] = $post->getSlug();
				$temp['category'] = $post->getCategory()->getId();
				$temp['categoryName'] = $post->getCategory()->getName();
				$temp['impact'] = $post->getUserImpact()->getId();
				$temp['impactName'] = $post->getUserImpact()->getArea();


				if($post->getPostStatus() == "REMOVED"){
					$temp['removed'] = true;
				}else{
					$temp['removed'] = false;
				}

				if ($post->getPostStatus() == "UNPUBLISHED") {
					$temp['unpublished'] = true;
				}else{
					$temp['unpublished'] = false;
				}

				if($post->getIsFeatured() == 1){
					$temp['featured'] = true;
				}else{
					$temp['featured'] = false;
				}

				if($post->getIsBreaking() == 1){
					$temp['breaking'] = true;
				}else{
					$temp['breaking'] = false;
				}

				if(null != $post->getSharedCount()){
					$temp['shares'] = $post->getSharedCount();
				}else{
					$temp['shares'] = 0;
				}

				if(!is_null($post->getPostDetails())){
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
		}

		$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();

		$data2 = array();
		if($categories){
			foreach ($categories as $categ) {
				$temp2['id'] = $categ->getId();
				$temp2['name'] = $categ->getName();

				$data2[] = $temp2;
			}
		}

		$impacts = $this->doctrine->em->getRepository('Entity\Impact')->findAll();

		$data3 = array();
		if($impacts){
			foreach ($impacts as $impac) {
				$temp3['id'] = $impac->getId();
				$temp3['name'] = $impac->getArea();

				$data3[] = $temp3;
			}
		}

		$response->setSuccess(true);
		$response->setData(array(
			'posts'=>$data,
			'categories'=>$data2,
			'impacts'=>$data3
		));
		$response->setError('');


		$response->respond();
		die();
	}

	function makeBreaking(){
		$response = new Response();
		try {
			$this->checkSession();

			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));

			if(null != $post){
				$post->setIsBreaking(true);

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

				foreach ($post->getFiles() as $filePath) {
					$file = base_url($filePath->getThumb());
					break;
				}

				$users = $this->doctrine->em->getRepository('Entity\User')->findAll();

				foreach ($users as $user) {
					$notification = new UserNotifications();
					$notification->setNotifyText($post->getHeadline()." has become a Breaking News");
					$notification->setLink(base_url()."single/".$post->getSlug());
					$notification->setUser($user);
					$notification->setImage($file);
					$notification->setActionType('post');
					$notification->setActionId($post->getId());

					if(null != $post->getAuthor()->getGcmId()){
						$gcm = new GCM();

						$message = array(
							'msg'=>$post->getHeadline()." has become a Breaking News",
							'action'=> array(
								'type'=>'post',
								'id'=>$post->getId()
							)
						);

						header("Content-Type: application/json");

						$result = $gcm->send_notification(array($post->getAuthor()->getGcmId()),$message);
					}

					$this->doctrine->em->persist($notification);
					$this->doctrine->em->flush();
				}

				$this->doctrine->em->persist($notification);
				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'Set Breaking'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post Not found'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}
		$response->respond();
		die();
	}

	function removeBreaking(){
		$response = new Response();
		try {
			$this->checkSession();

			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));

			if(null != $post){
				$post->setIsBreaking(false);

				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'Removed from Breaking'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post Not found'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}
		$response->respond();
		die();
	}

	function makeFeatured(){
		$response = new Response();
		try {
			$this->checkSession();

			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));

			if(null != $post){
				$post->setIsFeatured(true);

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
				$response->setData(array('msg'=>'Set Featured'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post Not found'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}
		$response->respond();
		die();
	}

	function removeFeatured(){
		$response = new Response();
		try {
			$this->checkSession();

			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));

			if(null != $post){
				$post->setIsFeatured(false);

				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'Removed from Featured'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post Not found'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}
		$response->respond();
		die();
	}

	function remove(){
		$response = new Response();
		$this->checkSession();
		try {
			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));
		
			if(null != $post){
				$post->setPostStatus('REMOVED');
				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'deleted'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post not found'
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

	function approve(){
		$response = new Response();
		$this->checkSession();
		try {
			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));
		
			if(null != $post){
				$post->setPostStatus('PUBLISHED');
				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'published'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post not found'
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



	function singlePost(){
		$this->checkSession();
		try {
			$anonymousPost = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_GET['id']));

			if(null != $anonymousPost){
				$data = array();

				$data['id'] = $anonymousPost->getId();
				$data['title'] = $anonymousPost->getHeadline();
				$data['description'] = $anonymousPost->getDescription();
				$data['date'] = $anonymousPost->getUpdatedOn()->format('d-M-Y');
				$data['file'] = array();

				foreach ($anonymousPost->getFiles() as $file) {
					$data['file'][] = $file->getFilePath();
					break;
				}

				$data['hashtags'] = array();
				foreach ($anonymousPost->getHashtags() as $hashtag) {
					$data['hashtags'][] = $hashtag->getHashtag();
 				}

 				$data['postType'] = $anonymousPost->getPostType();

 				$this->load->view('admin/header',array('links'=>array('style.css')));
 				$this->load->view('admin/anonymousPosts/singleAnonymousPage',array('postData'=>$data));
 				$this->load->view('admin/footer');

			}else{
				redirect('admin/anonymous?single=error');
			}
		} catch (Exception $e) {
			redirect('error');
		}
	}

	function unpublish(){
		$response = new Response();
		$this->checkSession();
		try {

			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));

			if(null != $post){

				$post->setPostStatus('UNPUBLISHED');

				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'Unpublished'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post Error'
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

	function publish(){
		$response = new Response();
		$this->checkSession();
		try {
			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST));
		
			if(null != $post){
				$post->setPostStatus('PUBLISHED');
				$this->doctrine->em->persist($post);
				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array('msg'=>'published'));
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>'Post not found'
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

	function changeCategory(){
		$response = new Response();
		try {
			$this->checkSession();

			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);
			$_POST = get_object_vars($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST['postId']));

			if($post){
				$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id'=>$_POST['categId']));

				if($category){
					$post->setCategory($category);

					$this->doctrine->em->persist($post);
					$this->doctrine->em->flush();

					$response->setSuccess(true);
					$response->setData(array('msg'=>'Category changed'));
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'Category not found'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>"Post Error"
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
		}
		$response->respond();
		die();
	}

	function changeImpact(){
		$response = new Response();
		try {
			$this->checkSession();

			$_POST = file_get_contents("php://input");
			$_POST = json_decode($_POST);
			$_POST = get_object_vars($_POST);

			$post = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_POST['postId']));

			if($post){
				$impact = $this->doctrine->em->getRepository('Entity\Impact')->findOneBy(array('id'=>$_POST['impactId']));

				if($impact){
					$post->setUserImpact($impact);

					$this->doctrine->em->persist($post);
					$this->doctrine->em->flush();

					$response->setSuccess(true);
					$response->setData(array('msg'=>'Impact changed'));
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array(
						'msg'=>'Impact not found'
					));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'msg'=>"Post Error"
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>$e->getMessage()));
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
}
?>