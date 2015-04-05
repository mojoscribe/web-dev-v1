<?php 
	class NewsByLocationController extends CI_Controller {
		function __construct(){
			parent::__construct();
		}
	
		function index(){
			try {

				if(isUserLoggedIn()){
					$user = isUserLoggedIn();

					$locations = $this->doctrine->em->getRepository('Entity\UserLocations')->findBy(array('user'=>$user));

					if($locations != null){

						$locationPosts = array();
						
						foreach ($locations as $location) {

							$tempLng = $location->getLongitude();

							$tempLat = $location->getLatitude();

							$posts = $this->doctrine->em->getRepository('Entity\Post')->getLocationBasedPosts($location->getLocationName());

							if(null != $posts){
								foreach ($posts as $post) {
									$temp = array();
									
									$temp['id'] = $post->getId();

									$temp['headline'] = $post->getHeadline();
									if($post->getIsAnonymous() == true){
										$temp['author'] = "Anonymous";
									}else{
										$temp['author'] = $post->getAuthor()->getUserName();
									}

									$temp['description'] = $post->getDescription();

									$temp['slug'] = $post->getSlug();

									$temp['date'] = $post->getUpdatedOn()->format('d-M-Y');

									$temp['files'] = array();

									foreach ($post->getFiles() as $file) {

										$tempFile = array();
										$tempFile['thumb'] = base_url($file->getThumb());
										$tempFile['small'] = base_url($file->getSmall());
										$tempFile['bigImage'] = base_url($file->getBig());
										$temp['files'][] = $tempFile;

									}

									if(null != $post->getLocation()){
										$tmpLoc = explode(',', $post->getLocation());

										if($tmpLoc[0] == $tmpLoc[1]){
											$temp['location'] = $tmpLoc[0];
										}else{
											$temp['location'] = $post->getLocation();
										}
									}

									$temp['hashtags'] = array();

									foreach ($post->getHashtags() as $hashtag) {
										$temp['hashtags'][] = $hashtag->getHashtag();
									}

									$temp['postType'] = $post->getPostType();

									if($post->getPostType() == "Video"){
										$temp['showVideo'] = true;
									}else{
										$temp['showVideo'] = false;
									}

									$locationPosts[] = $temp;
								}
							}
						}

						$profile = array();
						$profile['userName'] = $user->getUserName();
						$profile['picture'] = $user->getProfilePicturePath();

						$this->load->view('header',array('data'=>$profile));
						$this->load->view('navigation');
						$this->load->view('user/newsByLocation',array('posts'=>$locationPosts));
						$this->load->view('footer');

					}else{
						redirect('/');	
					}
				}else{
					redirect('/');	
				}
			} catch (Exception $e) {
				
			}
		}
	}
?>