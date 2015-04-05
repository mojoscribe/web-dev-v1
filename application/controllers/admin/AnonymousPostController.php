<?php 
use Entity\Post;
class AnonymousPostController extends CI_Controller {
	var $postRepository;
	function __construct(){
		parent::__construct();
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->load->file('application/classes/Response.php');
	}

	function index(){
		
		$this->checkSession();

		try {
			
		

			$this->load->view('admin/header');
			$this->load->view('admin/anonymousPosts/list');
			$this->load->view('admin/footer',array('tableId'=>'anonymous','scripts'=>array('js/controllers/admin/anonymousPostController.js')));

		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
			// redirect('error');
		}
	}

	function getAllPosts(){
		$response = new Response();
		try {
			$anonymousPosts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isAnonymous'=>1, 'postStatus'=>array("PUBLISHED",'REMOVED','UNPUBLISHED')));

			$i = 1;
			$data = array();
			foreach ($anonymousPosts as $anonymousPost) {
				$temp['id'] = $anonymousPost->getId();
				$temp['serial'] = $i++;
				$temp['title'] = $anonymousPost->getHeadline();
				$temp['mediaType'] = $anonymousPost->getPostType();
				$temp['date'] = $anonymousPost->getUpdatedOn()->format('d-M-Y');
				$temp['author'] = $anonymousPost->getAuthor()->getUserName();
				$temp['slug'] = $anonymousPost->getSlug();
				$temp['category'] = $anonymousPost->getCategory()->getId();
				$temp['categoryName'] = $anonymousPost->getCategory()->getName();
				$temp['impact'] = $anonymousPost->getUserImpact()->getId();
				$temp['impactName'] = $anonymousPost->getUserImpact()->getArea();


				if($anonymousPost->getPostStatus() == "REMOVED"){
					$temp['removed'] = true;
				}else{
					$temp['removed'] = false;
				}

				if ($anonymousPost->getPostStatus() == "UNPUBLISHED") {
					$temp['unpublished'] = true;
				}else{
					$temp['unpublished'] = false;
				}

				if($anonymousPost->getIsFeatured() == 1){
					$temp['featured'] = true;
				}else{
					$temp['featured'] = false;
				}

				if($anonymousPost->getIsBreaking() == 1){
					$temp['breaking'] = true;
				}else{
					$temp['breaking'] = false;
				}

				if(null != $anonymousPost->getSharedCount()){
					$temp['shares'] = $anonymousPost->getSharedCount();
				}else{
					$temp['shares'] = 0;
				}

				if(null != $anonymousPost->getPostDetails()){
					if(null != $anonymousPost->getPostDetails()->getNumberOfViews()){
						$temp['views'] = $anonymousPost->getPostDetails()->getNumberOfViews();
					}else{
						$temp['views'] = 0;
					}

					if(null != $anonymousPost->getPostDetails()->getRating()){
						$temp['rating'] = $anonymousPost->getPostDetails()->getRating();
					}else{
						$temp['rating'] = 0;
					}

				}else{
					$temp['views'] = 0;
					$temp['shares'] = 0;
					$temp['rating'] = 0;
				}

				$flags = $this->doctrine->em->getRepository('Entity\FlagLog')->findBy(array('post'=>$anonymousPost));

				if(null != $flags){
					$temp['flags'] = count($flags);
				}else{
					$temp['flags'] = 0;
				}

				$data[] = $temp;
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

	function delete(){
		$this->checkSession();
		try {
			$anonymousPost = $this->doctrine->em->getRepository('Entity\Post')->findOneBy(array('id'=>$_GET['id']));
		
			if(null == $anonymousPost->getAuthor()){
				$this->doctrine->em->remove($anonymousPost);
				$this->doctrine->em->flush();
			}else{
				redirect('admin/anonymous?delete=error');
			}
		} catch (Exception $e) {
			redirect('error');
		}
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
				$post->setPostStatus('Publish');
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