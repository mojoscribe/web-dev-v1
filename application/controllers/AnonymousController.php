<?php 
class AnonymousController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 	}
 
 	function index(){
 		try {
 			$this->load->library('user_agent');

 			$cookie = null;
 			if(null != $_COOKIE && isset($_COOKIE['click'])){
				$cookie = $_COOKIE['click'];
			}

			if($this->agent->is_mobile() && !isset($cookie)){
				$this->load->view('mobile/android.html');
			}else{
				if(isUserLoggedIn()){
					$user = isUserLoggedIn();

					$email = $user->getEmail();
					$profile['userName'] = $user->getUserName();
					$profile['picture'] = $user->getProfilePicturePath();

					$this->load->view('header',array('data'=>$profile));
				}else{
			 		$this->load->view('header');
				}

				$posts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('isAnonymous'=>1,'postStatus'=>'PUBLISHED'),array('postRanking'=>'desc'),3);

				$data = array();
				if($posts){
					
					foreach ($posts as $post) {
						$temp['id'] = $post->getId();
						$temp['headline'] = $post->getHeadline();
						$temp['files'] = array();

						foreach ($post->getFiles() as $file) {
							$tmp['id'] = $file->getId();
							$tmp['file'] = base_url().$file->getThumb();

							$temp['files'][] = $tmp;
						}

						$temp['date'] = $post->getUpdatedOn()->format('d-M-Y');

						$temp['slug'] = $post->getSlug();

						$temp['hashtags'] = array();

						foreach ($post->getHashtags() as $hashtag) {
							$tmp1['id'] = $hashtag->getId();
							$tmp1['hashtag'] = $hashtag->getHashtag();

							$temp['hashtags'][] = $tmp1;
						}

						$temp['postType'] = $post->getPostType();

						$data[] = $temp;
					}
				}

		 		$this->load->view('pages/anonymousNewsRoom',array('posts'=>$data));
		 		$this->load->view('footer',array('scripts'=>array('controllers/headercontroller.js')));
	 		}
 		} catch (Exception $e) {
 			echo "<pre>";
 			print_r($e->getMessage());
 			die();
 		}
 	}
 } ?>