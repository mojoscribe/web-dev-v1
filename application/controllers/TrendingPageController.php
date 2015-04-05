<?php 
class TrendingPageController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 	}
 
 	function index(){
 		try {
 			
 			$posts = $this->doctrine->em->getRepository('Entity\Post')->getTrendingNewsPosts();

 			if($posts){
 				$data = array();
				foreach ($posts as $post) {
					$temp['id'] = $post->getId();
					$temp['title'] = null;
					$temp['title'] = $post->getHeadline();
					$temp['description'] = $post->getDescription();
					$date = $post->getUpdatedOn();
					$date = $date->format('d-M-Y');
					$temp['date'] = $date;
					if(null != $post->getAuthor()){
						if($post->getIsAnonymous() == true){
							$temp['author'] = "Anonymous";	
						}else{
							$temp['author'] = $post->getAuthor()->getUserName();									
						}
					}

					$temp['hashtags'] = array();

					foreach ($post->getHashtags() as $hashtag) {
						$temp['hashtags'][] = $hashtag->getHashtag();
					}

					$temp['files'] = array();

					foreach ($post->getFiles() as $file) {
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
					$temp['slug'] = $post->getSlug();

					$temp['postType'] = $post->getPostType();

					if($post->getPostType() == "Image"){
						$temp['showImage'] = true;
						$temp['showVideo'] = false;
					}elseif($post->getPostType() == "Video"){
						$temp['showVideo'] = true;
						$temp['showImage'] = false;
					}

					if(!is_null($post->getPostDetails())){
						$temp['views'] = $post->getPostDetails()->getNumberOfViews();

						if(null != $post->getPostDetails()->getRating()){
							$temp['rating'] = round($post->getPostDetails()->getRating(),1);
						}else{
							$temp['rating'] = 0;
						}
					}else{
						$temp['views'] = 0;
						$temp['rating'] = 0;
					}

					$data[] = $temp;
				}
 			}

 			if(isUserLoggedIn()){
 				$user = isUserLoggedIn();

 				$profile = array();
 				$profile['userName'] = $user->getUserName();
 				$profile['picture'] = $user->getProfilePicturePath();

	 			$this->load->view('header',array('data'=>$profile));
	 		}else{
	 			$this->load->view('header');
	 		}

	 		$this->load->view('trending',array('posts'=>$data));
	 		$this->load->view('footer');
	 		
 		} catch (Exception $e) {
 			
 		}
 	}
 } ?>