<?php
class dashboardcontroller extends CI_Controller {
	var $postRepository;
	function __construct() {
		parent::__construct();
		$this->load->file('application/classes/Response.php');
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
	}

	function index() {
		try {
			$this->load->library('user_agent');

			$cookie = null;
 			if(null != $_COOKIE && isset($_COOKIE['click'])){
				$cookie = $_COOKIE['click'];
			}

			if($this->agent->is_mobile() && !isset($cookie)){
				$this->load->view('mobile/android.html');
			}else{

				$postRepository = $this -> doctrine -> em -> getRepository('Entity\Post');
				$posts = $postRepository -> getPublishedPosts();

				$data = array();
				$count = count($posts);			
				if($count >= 5) {
					$count = 5;
				}

				if(false != $posts){
					for ($i=0; $i < $count ; $i++) { 				
						$temp['id'] = $posts[$i]->getId();
						$temp['title'] = null;
						$temp['title'] = $posts[$i]->getHeadline();
						$temp['description'] = $posts[$i]->getDescription();
						$date = $posts[$i]->getUpdatedOn();
						$date = $date->format('d-M-Y');
						$temp['date'] = $date;
						
						$temp['author'] = null;
						if($posts[$i]->getIsAnonymous() == false){
							$temp['author'] = $posts[$i]->getAuthor()->getUserName();
						}else{
							$temp['author'] = "Anonymous";
						}

						$temp['hashtags'] = array();

						foreach ($posts[$i]->getHashtags() as $hashtag) {
							$temp['hashtags'][] = $hashtag->getHashtag();
						}

						$temp['files'] = array();

						foreach ($posts[$i]->getFiles() as $file) {
							$temp['files'][] = $file->getFilePath();
							break;
						}
						$temp['slug'] = $posts[$i]->getSlug();

						$temp['postType'] = $posts[$i]->getPostType();

						// $temp['source'] = $posts[$i]->getSourceOfMedia();

						$data[] = $temp;
					}
				}
				
				if(false != isUserLoggedIn()){
					redirect('dashboard');
				} else {
					
					$this -> load -> view('header');
					$this->load->view('navigation');
					$this->load->view('trendingView');
					$this -> load -> view('landing',array('postsData'=>$data));
					$this -> load -> view('footer', array('scripts' => array('validations/regLoginValidations.js','controllers/homeBreakingNewscontroller.js','controllers/trendingNewscontroller.js','controllers/featuredcontroller.js','trending.js')));
				}
			}
		} catch(Exception $e) {
			// print_r($e -> getMessage());
			redirect('technicalProblem');
		}
	}

	function getTrendingPosts(){
		
		$response = new Response();
		
		if(false != checkCsrf()){

			
			if($_SERVER['REQUEST_METHOD'] == "GET"){

				$breakingPosts = $this->postRepository->getTrendingNewsPosts();

				if(!empty($breakingPosts)){
					$data = array();

					$count = count($breakingPosts);			
					if($count >= 21) {
						$count = 21;
					}

					for ($i=0; $i < $count ; $i++) { 				
						$temp['id'] = $breakingPosts[$i]->getId();						
						$temp['title'] = $breakingPosts[$i]->getHeadline();
						$temp['description'] = $breakingPosts[$i]->getDescription();
						$date = $breakingPosts[$i]->getUpdatedOn();
						$date = $date->format('d-M-Y H:i:s');
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

						$temp['dateObject'] = $breakingPosts[$i]->getUpdatedOn();

						$temp['timeZone'] = '';

						if($breakingPosts[$i]->getAuthor()->getTimeZone()){
							$temp['timeZone'] = $breakingPosts[$i]->getAuthor()->getTimeZone();
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

					$response->setSuccess(true);
					$response->setData(array('breakingNewsData'=>$data));
					$response->setError('');
				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'No posts for now'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Method Error'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Dude! No Cross domain requests are allowed'));
		}

		$response->respond();
		die();
	}

	function getBreakingPosts(){
		
		$response = new Response();
		
		if(false != checkCsrf()){
			
			if($_SERVER['REQUEST_METHOD'] == "GET"){

				$breakingPosts = $this->postRepository->getBreakingNewsPosts();

				if(!empty($breakingPosts)){
					$data = array();

					$count = count($breakingPosts);			
					if($count >= 7) {
						$count = 7;
					}

					for ($i=0; $i < $count ; $i++) { 				
						$temp['id'] = $breakingPosts[$i]->getId();						
						$temp['title'] = $breakingPosts[$i]->getHeadline();
						$temp['description'] = $breakingPosts[$i]->getDescription();
						// $date = $date->format('d-M-Y');
						$temp['date'] = $breakingPosts[$i]->getUpdatedOn()->format('d-M-Y H:i:s');
						// $temp['timeZone'] = '';
						// if(null != $breakingPosts[$i]->getAuthor()->getTimeZone()){
						// 	$temp['timeZone'] = $breakingPosts[$i]->getAuthor()->getTimeZone();
						// }
						
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

						// $temp['dateObject'] = $breakingPosts[$i]->getUpdatedOn();

						// $temp['timeZone'] = '';

						// if($breakingPosts[$i]->getAuthor()->getTimeZone()){
						// 	$temp['timeZone'] = $breakingPosts[$i]->getAuthor()->getTimeZone();
						// }

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


					$response->setSuccess(true);
					$response->setData(array('breakingNewsData'=>$data));
					$response->setError('');

				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'No posts for now'));
				}
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Method Error'));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Dude! No Cross domain requests are allowed'));
		}

		$response->respond();
		die();
	}

	function getCategories(){
		$response = new Response();
		// if(false != checkCsrf()){
			try {
				if($_SERVER['REQUEST_METHOD'] == "POST"){
					$categories = $this->doctrine->em->getRepository('Entity\Category')->findAll();

					$data = array();

					foreach ($categories as $category) {
						$temp['id'] = $category->getId();
						$temp['name'] = $category->getName();

						$data[] = $temp;
					}

					$response->setSuccess(true);
					$response->setData($data);
					$response->setError('');

				}else{
					$response->setSuccess(false);
					$response->setData('');
					$response->setError(array('msg'=>'Method Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>'Something went wrong!'));	
			}
		// }else{
		// 	$response->setSuccess(false);
		// 	$response->setData('');
		// 	$response->setError(array('msg'=>'Dude! No Cross domain requests are allowed'));
		// }
		$response->respond();
		die();
	}

	function getTrendingHashtagsList(){
		$response = new Response();
		try {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$trendingList = $this->doctrine->em->getRepository('Entity\TrendingHashtags')->findBy(array(),array('id'=>'desc'),10);

				$data = array();
				foreach ($trendingList as $trending) {
					$temp['id'] = $trending->getId();
					$temp['rank'] = $trending->getHashtagRank();
					$temp['name'] = $trending->getName();

					$data[] = $temp;
				}

				$response->setSuccess(true);
				$response->setData($data);
				$response->setError('');
			}else{
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array(
					'code'=>1100,
					'msg'=>'Method Error'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array(
				'code'=>'',
				'msg'=>$e->getMessage()
			));
		}
		$response->respond();
		die();
	}
}
