<?php 
class FeaturedPageController extends CI_Controller {
	var $postRepository;
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
	}

	function index(){
		try {

			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();

				$profile['id'] = $user->getId();
				$profile['picture'] = $user->getProfilePicturePath();
				$profile['userName'] = $user->getUserName();

				$this->load->view('header',array('data'=>$profile));
				// $this->load->view('user/header');
				$this->load->view('navigation');
				$this->load->view('featured');
				$this->load->view('footer',array('scripts'=>array('controllers/featuredcontroller.js')));
			}else{
				$this->load->view('header');
				$this->load->view('navigation');
				$this->load->view('featured');
				$this->load->view('footer',array('scripts'=>array('controllers/featuredcontroller.js')));

			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function getFeaturedPosts(){
		$response = new Response();
		if(false != checkCsrf()){
		
			try {
				if($_SERVER['REQUEST_METHOD'] == "GET"){
					$featuredPosts = $this->postRepository->getFeaturedPosts();

					if(false != $featuredPosts){
						$data = array();
						foreach ($featuredPosts as $featured) {
							$temp['id'] = $featured->getId();
							$temp['title'] = null;
							$temp['title'] = $featured->getHeadline();
							$temp['description'] = $featured->getDescription();
							$date = $featured->getUpdatedOn();
							$date = $date->format('d-M-Y H:i:s');
							$temp['date'] = $date;
							if(null != $featured->getAuthor()){
								if($featured->getIsAnonymous() == true){
									$temp['author'] = "Anonymous";	
								}else{
									$temp['author'] = $featured->getAuthor()->getUserName();									
								}
							}

							$temp['hashtags'] = array();

							foreach ($featured->getHashtags() as $hashtag) {
								$temp['hashtags'][] = $hashtag->getHashtag();
							}

							$temp['files'] = array();

							foreach ($featured->getFiles() as $file) {
								//$temp['files'][] = $file->getFilePath();
								$tempFile = array();
								$tempFile['small'] = base_url($file->getSmall());
								$tempFile['thumb'] = base_url($file->getThumb());
								$tempFile['long'] = base_url($file->getLong());
								$tempFile['big'] = base_url($file->getBig());								
								
								$temp['files'][] = $tempFile;

								/*$tempFile = array();
								$tempFile['thumb'] = base_url($file->getThumb());
								$tempFile['medium'] = base_url($file->getMedium());
								$tempFile['bigImage'] = base_url($file->getBigImage());
								//$temp['files'][] = $file->getFilePath();
								$temp['files'][] = $tempFile;*/
								//break;
							}
							$temp['slug'] = $featured->getSlug();

							$temp['postType'] = $featured->getPostType();

							if($featured->getPostType() == "Image"){
								$temp['showImage'] = true;
								$temp['showVideo'] = false;
							}elseif($featured->getPostType() == "Video"){
								$temp['showVideo'] = true;
								$temp['showImage'] = false;
							}

							if(!is_null($featured->getPostDetails())){
								$temp['views'] = $featured->getPostDetails()->getNumberOfViews();

								if(null != $featured->getPostDetails()->getRating()){
									$temp['rating'] = round($featured->getPostDetails()->getRating(),1);
								}else{
									$temp['rating'] = 0;
								}
							}else{
								$temp['views'] = 0;
								$temp['rating'] = 0;
							}

							$data[] = $temp;
						}


						$response->setSuccess(true);
						$response->setData($data);
						$response->setError('');

					}else{
						$response->setSuccess(false);
						$response->setData('');
						$response->setError(array('msg'=>'No posts'));
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
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain are not allowed'));
		}
		$response->respond();
		die();
	}

}
?>