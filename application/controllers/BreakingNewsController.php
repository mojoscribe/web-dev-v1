<?php 
	class BreakingNewsController extends CI_Controller {
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
					
					$data = array();

					$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');

					$breakingPosts = $this->postRepository->getBreakingNewsPosts();

					if(!empty($breakingPosts)){

						$count = count($breakingPosts);			
						if($count >= 7) {
							$count = 7;
						}

						for ($i=0; $i < $count ; $i++) { 				
							$temp['id'] = $breakingPosts[$i]->getId();						
							$temp['headline'] = $breakingPosts[$i]->getHeadline();
							$temp['description'] = $breakingPosts[$i]->getDescription();
							$date = $breakingPosts[$i]->getUpdatedOn();
							$date = $date->format('d-M-Y');
							$temp['date'] = $date;
							
							if($breakingPosts[$i]->getIsAnonymous() == true){
								$temp['author'] = "Anonymous";
							}else{
								$temp['author'] = $breakingPosts[$i]->getAuthor()->getUserName();
							}

							$temp['hashtags'] = array();

							if(null != $breakingPosts[$i]->getHashtags()){

								foreach ($breakingPosts[$i]->getHashtags() as $hashtag) {
									$temp['hashtags'][] = $hashtag->getHashtag();
								}
							}

							$temp['files'] = array();

							if(null != $breakingPosts[$i]->getFiles()){
								
								foreach ($breakingPosts[$i]->getFiles() as $file) {
									$tempFile = array();
									$tempFile['small'] = base_url($file->getSmall());
									$tempFile['thumb'] = base_url($file->getThumb());
									$tempFile['long'] = base_url($file->getLong());
									$tempFile['big'] = base_url($file->getBig());								
									
									$temp['files'][] = $tempFile;
									
								}
							}

							if(null != $breakingPosts[$i]->getSharedCount()){
								$temp['numberOfShares'] = $breakingPosts[$i]->getSharedCount();
							}else{
								$temp['numberOfShares'] = 0;
							}

							$temp['slug'] = $breakingPosts[$i]->getSlug();

							$temp['postType'] = $breakingPosts[$i]->getPostType();

							if(null != $breakingPosts[$i]->getPostDetails()){
								$temp['views'] = $breakingPosts[$i]->getPostDetails()->getNumberOfViews();

								$postDetails = $breakingPosts[$i]->getPostDetails();

								$positive_sum = $postDetails->getPositive_sum();
								$negative_sum = $postDetails->getNegative_sum();

								if(!is_null($postDetails->getPositive_sum()) || !is_null($postDetails->getNegative_sum()) || 0 != $postDetails->getPositive_sum() || 0 != $postDetails->getNegative_sum()){
									$exp1 = ($positive_sum + 1.9208)/($positive_sum + $negative_sum);

									$exp21 = ($positive_sum * $negative_sum)/($positive_sum + $negative_sum);
									$exp21 = $exp21 + 0.9604;
									$exp2 = (1.96 * sqrt($exp21))/($positive_sum + $negative_sum);

									$exp3 = 1 + 3.8416/($positive_sum + $negative_sum);

									$exp = ($exp1 - $exp2)/$exp3;

									$averageRating = $exp * 1;

									$temp['rates'] = round($averageRating,2) * 10;
								}else{
									$temp['rates'] = 0;
								}

							}else{
								$temp['views'] = 0;

								$temp['rates'] = 0;
							}

							$data[] = $temp;
						}
					}

					if(isUserLoggedIn()){

						$user = isUserLogegdIn();
						$profile = array();
						$profile['userName'] = $user->getUserName();
						$profile['picture'] = $user->getProfilePicturePath();

						$this->load->view('header',array('data'=>$profile));
					}else{
						$this->load->view('header');
					}

					$this->load->view('navigation');
					$this->load->view('pages/breakingNews',array('posts'=>$data));
					$this->load->view('footer');
				}
			} catch (Exception $e) {
				
			}
		}
	}
?>