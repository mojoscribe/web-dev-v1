<?php 
class CategoryPageController extends CI_Controller {
	var $postRepository;
	var $categoryRepository;
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
 		$this->categoryRepository = $this->doctrine->em->getRepository('Entity\Category');
 	}

 	function index(){
 		$this->load->library('user_agent');

		$cookie = null;
			if(null != $_COOKIE && isset($_COOKIE['click'])){
			$cookie = $_COOKIE['click'];
		}

		if($this->agent->is_mobile() && !isset($cookie)){
			$this->load->view('mobile/android.html');
		}else{
	 		if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();

				$profile['id'] = $user->getId();
				$profile['picture'] = $user->getProfilePicturePath();
				$profile['userName'] = $user->getUserName();

		 		$this->load->view('header',array('data'=>$profile));
		 		// $this->load->view('user/header');
		 		$this->load->view('navigation');
		 		$this->load->view('category');
		 		$this->load->view('footer',array('scripts'=>array('controllers/categorycontroller.js')));
		 	}else{

		 		$this->load->view('header');
		 		$this->load->view('navigation');
		 		$this->load->view('category');
		 		$this->load->view('footer',array('scripts'=>array('controllers/categorycontroller.js')));
		 	}
		}
 	}
 
 	function getPosts(){
 		$response = new Response();
 		if(false != checkCsrf()){
	 		try {

	 			$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id'=>$_GET['categId']));
	 			if(null != $category){

	 				$posts = $this->postRepository->getPostsByCategory($category);
	 				$noPosts = true;
					$data = array();

					if(false != $posts){
						foreach ($posts as $post) {

							$temp['id'] = $post->getId();
							if($post->getIsAnonymous() == true){
								$temp['author'] = "Anonymous";
							}else{
								$temp['author'] = $post->getAuthor()->getUserName();
							}
							$temp['title'] = $post->getHeadline();
							$temp['category'] = $post->getCategory()->getName();
							$temp['impact'] = $post->getUserImpact()->getArea();
							$temp['date'] = $post->getCreatedOn()->format('d-M-Y H:i:s');
							// $temp['files'] = array();
							foreach ($post->getFiles() as $file) {
								$tempFile = array();
								$tempFile['thumb'] = base_url($file->getThumb());
								$temp['files'] = $tempFile['thumb'];
								//$temp['files'][] = $file->getFilePath();
								//break;
							}

							$temp['description'] = $post->getDescription();

							$temp['hashtags'] = array();
							foreach ($post->getHashtags() as $hashtag) {
								$temp['hashtags'][] = $hashtag->getHashtag();
							}

							if($post->getPostType() == "Video"){
								$temp['showVideo'] = true;
							}else{
								$temp['showVideo'] = false;
							}

							$temp['slug'] = $post->getSlug();

							$data[] = $temp;
						}

						$response->setSuccess(true);
					 	$response->setData(array(
					 		'data'=>$data,
					 		'category'=>$category->getName()
					 	));
					 	$response->setError(null);
						// $noPosts = false;
					}else{
						$response->setSuccess(false);
					 	$response->setData(null);
					 	$response->setError(array(
					 		'code'=>'',
					 		'msg'=>'No posts for the data',
					 		'category'=>$category->getName()
					 	));
					}

					// if(false != isUserLoggedIn()){
					// 	$user = isUserLoggedIn();

					// 	$profile['id'] = $user->getId();
					// 	$profile['picture'] = $user->getProfilePicturePath();
					// 	$profile['userName'] = $user->getUserName();

				 // 		$this->load->view('header',array('data'=>$profile));
				 // 		// $this->load->view('user/header');
				 // 		$this->load->view('navigation');
				 // 		$this->load->view('category',array('posts'=>$data,'category'=>$category->getName(),'noPosts'=>$noPosts));
				 // 		$this->load->view('footer',array('scripts'=>array('controllers/categorycontroller.js')));
				 // 	}else{

				 // 		$this->load->view('header');
				 // 		$this->load->view('navigation');
				 // 		$this->load->view('category',array('posts'=>$data,'category'=>$category->getName(),'noPosts'=>$noPosts));
				 // 		$this->load->view('footer',array('scripts'=>array('controllers/categorycontroller.js')));
				 // 	}


				}else{

					// redirect('notFound');
					$response->setSuccess(false);
			 		$response->setData(null);
			 		$response->setError(array(
			 			'code'=>404,
			 			'msg'=>'Category not found'
			 		));
				}
	 		} catch (Exception $e) {
	 			// redirect('technicalProblem');
	 			$response->setSuccess(false);
		 		$response->setData(null);
		 		$response->setError(array(
		 			'code'=>500,
		 			'msg'=>$e->getMessage()
		 		));	
	 		}
	 	}else{
	 		$response->setSuccess(false);
	 		$response->setData(null);
	 		$response->setError(array(
	 			'code'=>401,
	 			'msg'=>'Cross domain requests are not allowed'
	 		));
	 	}

	 	$response->respond();
	 	die();
 	}

 	function getPostsByCategory(){
 		$response = new Response();
 		if (false != checkCsrf()) {
 			try {
 				if($_SERVER['REQUEST_METHOD'] == "POST"){
 					$category = $this->categoryRepository->getCategory($_POST['categoryId']);

 					if(false != $category){
 						$posts = $this->postRepository->getPostsByCategory($category);

 						if(false != $posts){
 							$data = array();
 							foreach ($posts as $post) {
 								$temp['id'] = $post->getId();
 								if($post->getIsAnonymous() == true){
									$temp['author'] = "Anonymous";
								}else{
									$temp['author'] = $post->getAuthor()->getUserName();
								}
 								$temp['category'] = $post->getCategory()->getName();
 								$temp['impact'] = $post->getImpact()->getArea();
 								$temp['files'] = array();
 								foreach ($post->getFiles() as $file) {
 									$temp['files'][] = $file->getFilePath();
 									break;
 								}

 								$temp['description'] = $post->getDescription();

 								$temp['hashtags'] = array();
 								foreach ($post->getHashtags() as $hashtag) {
 									$temp['hashtags'][] = $hashtag->getHashtag();
 								}

 								$temp['postType'] = $post->getPostType();
 								$temp['slug'] = $post->getSlug();

 								$data[] = $temp;
 							}

 							$response->setSuccess(true);
 							$response->setData($data);
 							$response->setError('');
 						}else{
 							$response->setSuccess(false);
 							$response->setData('');
 							$response->setError(array('msg'=>'Oops! No posts were found'));
 						}
 					}else{
 						$response->setSuccess(false);
 						$response->setData('');
 						$response->setError(array('msg'=>'Something is wrong! Category not found'));
 					}
 				}else{
 					$response->setSuccess(false);
 					$response->setData('');
 					$response->setError(array('msg'=>'Method Error'));
 				}
 			} catch (Exception $e) {
 				$response->setSuccess(false);
 				$response->setData('');
 				$response->setError(array('msg'=>$e->getMessage()));
 			}
 		} else {
 			$response->setSuccess(false);
 			$response->setData('');
 			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
 		}
 		$response->respond();
 		die();
 	}

 	function categoryPage(){
 		try {
	 		if(isUserLoggedIn()){
				$user = isUserLoggedIn();

				$profile['id'] = $user->getId();
				$profile['picture'] = $user->getProfilePicturePath();
				$profile['userName'] = $user->getUserName();

		 		$this->load->view('header',array('data'=>$profile));
		 		$this->load->view('navigation');
		 		$this->load->view('pages/categories');
		 		$this->load->view('footer',array('scripts'=>array('scroller.js','controllers/categorycontroller.js')));
			}else{
				$this->load->view('header');
				$this->load->view('navigation');
		 		$this->load->view('pages/categories');
		 		$this->load->view('footer',array('scripts'=>array('scroller.js','controllers/categorycontroller.js')));
			}
 		} catch (Exception $e) {
			redirect('technicalProblem'); 			
 		}
 	}

 	function getPagePosts(){
 		$response = new Response();
 		if(false != checkCsrf()){
	 		try {
	 			if($_SERVER['REQUEST_METHOD'] == "GET"){
	 				$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();

	 				$categoriesData = array();
	 				foreach ($categories as $preference) {

						$preferredPosts = $this->postRepository->getPostsByCategory($preference);

						$data = array();

						$count2 = count($preferredPosts);

						if($count2 >=6){
							$count2 = 6;
						}

						if(!is_null($preferredPosts) && !empty($preferredPosts)){

							for ($i=0; $i < $count2; $i++) { 
								$temp['id'] = $preferredPosts[$i]->getId();
								$temp['headline'] = $preferredPosts[$i]->getHeadline();
								$temp['slug'] = $preferredPosts[$i]->getSlug();
								if($preferredPosts[$i]->getIsAnonymous() == true){
									$temp['author'] = "Anonymous";
								}else{
									$temp['author'] = $preferredPosts[$i]->getAuthor()->getUserName();
								}

								$temp['date'] = $preferredPosts[$i]->getUpdatedOn()->format('d-M-Y H:i:s');

								$temp['files'] = array();

								foreach ($preferredPosts[$i]->getFiles() as $file) {
									/*$tmp['id'] = $file->getId();
									$tmp['file'] = $file->getFilePath();

									$temp['files'][] = $tmp;*/

									$tempFile = array();
									$tempFile['thumb'] = base_url($file->getThumb());
									$tempFile['medium'] = base_url($file->getSmall());
									$tempFile['bigImage'] = base_url($file->getBig());
									//$temp['files'][] = $file->getFilePath();
									$temp['files'] = $tempFile['thumb'];
									break;
								}

								$temp['description'] = $preferredPosts[$i]->getDescription();

								$temp['hashtags'] = array();
								foreach ($preferredPosts[$i]->getHashtags() as $hashtag) {
									$temp['hashtags'][] = $hashtag->getHashtag();
								}

								if($preferredPosts[$i]->getPostType() == "Video"){
									$temp['showVideo'] = true;
								}else{
									$temp['showVideo'] = false;
								}

								$data['posts'][] = $temp;
								$data['id'] = $preference->getId();
								$data['name'] = $preference->getName();	
							}
						$categoriesData[] = $data;
							
						}
					}

					$response->setSuccess(true);
					$response->setData(array(
						'data'=>$categoriesData
					));
					$response->setError(null);

					// if(isUserLoggedIn()){
					// 	$user = isUserLoggedIn();

					// 	$profile['id'] = $user->getId();
					// 	$profile['picture'] = $user->getProfilePicturePath();
					// 	$profile['userName'] = $user->getUserName();

				 // 		$this->load->view('header',array('data'=>$profile));
				 // 		$this->load->view('navigation');
				 // 		$this->load->view('pages/categories',array('categoriesData'=>$categoriesData));
				 // 		$this->load->view('footer',array('scripts'=>array('scroller.js')));
					// }else{
					// 	$this->load->view('header');
					// 	$this->load->view('navigation');
				 // 		$this->load->view('pages/categories',array('categoriesData'=>$categoriesData));
				 // 		$this->load->view('footer',array('scripts'=>array('scroller.js')));
					// }
	 			}else{
	 				// redirect('technicalProblem');
	 				$response->setSuccess(false);
	 				$response->setData(null);
	 				$response->setError(array(
	 					'code'=>401,
	 					'msg'=>'Method Error'
	 				));
	 			}
	 		} catch (Exception $e) {
	 			// redirect('technicalProblem');
	 			$response->setSuccess(false);
	 			$response->setData(null);
	 			$response->setError(array(
	 				'code'=>500,
	 				'msg'=>$e->getMessage()
	 			));
	 		}
	 	}else{
	 		$response->setSuccess(false);
	 		$response->setData(null);
	 		$response->setError(array(
	 			'code'=>401,
	 			'msg'=>'Cross domain requests are not allowed'
	 		));
	 	}

	 	$response->respond();
	 	die();
 	}
 }

 ?>