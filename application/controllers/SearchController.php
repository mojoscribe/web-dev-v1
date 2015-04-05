<?php
use Entity\Post;

class SearchController extends CI_Controller {
	var $postRepository;
	function __construct() {
		parent::__construct();
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->load->file('application/classes/Response.php');
	}

	function index(){
		try {

			$q = $_GET['q'];

			if(false != isUserLoggedIn()){

				$user = isUserLoggedIn();

				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$profile));
				$this->load->view('navigation',array('searchQuery'=>$q));
				$this->load->view('user/resultPage');
				$this->load->view('footer',array('scripts'=>array('controllers/searchController.js')));

			}else{
				$this->load->view('header');
				$this->load->view('navigation',array('searchQuery'=>$q));
				$this->load->view('user/resultPage');
				$this->load->view('footer',array('scripts'=>array('controllers/searchController.js')));
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function searchResults() {
		$response = new Response();
		if(false != checkCsrf()){
			try {
				if(isset($_GET['q'])){
					$q = $_GET['q'];

					$q = strip_tags($q);

					$hashtag = $this->doctrine->em->getRepository('Entity\Hashtags')->findOneBy(array('hashtag'=>$q));

					$searchResults = array();

					/*if(null == $hashtag){
						$searchResults = $this->postRepository->getSearchResults($q);				
					}else{
						$searchResults = $this->postRepository->getSearchResultsByHashtags($q,$hashtag);
					}*/

					if(null != $hashtag){
						$searchResults = $this->postRepository->getSearchResultsByHashtags($q,$hashtag);
					}else{
						$searchResults = array();
					}

					$searchResults2 = $this->postRepository->getSearchResults($q);

					if(null != $searchResults2){
						foreach($searchResults2 as $res) {
							if(!in_array($res, $searchResults)){
								$searchResults[] = $res;
							}
						}
					}

					if(!empty($searchResults)){
						// $searchResults = array_unique($searchResults, SORT_REGULAR);
						$searchResults = $this->super_unique($searchResults);
					}

					$data = array();
					if(null != $searchResults){
						foreach ($searchResults as $searchResult) {
							$temp['id'] = $searchResult->getId();
							$temp['title'] = $searchResult->getHeadline();
							$temp['description'] = $searchResult->getDescription();
							$date = $searchResult->getUpdatedOn();
							$date = $date->format('d-M-Y H:i:s');
							$temp['date'] = $date;

							if($searchResult->getIsAnonymous() == true){
								$temp['author'] = "Anonymous";	
							}else{
								$temp['author'] = $searchResult->getAuthor()->getUserName();
							}
							
							$temp['hashtags'] = array();

							foreach ($searchResult->getHashtags() as $hashtag) {
								$temp['hashtags'][] = $hashtag->getHashtag();
							}

							$temp['files'] = array();

							foreach ($searchResult->getFiles() as $file) {
								$temp['files'] = base_url($file->getThumb());
								break;
							}
							$temp['slug'] = $searchResult->getSlug();

							if($searchResult->getPostType() == "Video"){
								$temp['showVideo'] = true;
							}else{
								$temp['showVideo'] = false;
							}

							$data[] = $temp;
						}	
					}else{
						$data = $q;
					}

					$response->setSuccess(true);
					$response->setData(array(
						'data'=>$data
					));
					$response->setError(null);
				}else{
					$response->setSuccess(false);
					$response->setData(null);
					$response->setError(array(
						'code'=>'',
						'msg'=>'Query not set'
					));
				}

				// if(false != isUserLoggedIn()){

				// 	$user = isUserLoggedIn();

				// 	$profile['userName'] = $user->getUserName();
				// 	$profile['picture'] = $user->getProfilePicturePath();

				// 	$this->load->view('header',array('data'=>$profile));
				// 	$this->load->view('navigation',array('searchQuery'=>$q));
				// 	$this->load->view('user/resultPage',array("searchResults" => $data));
				// 	$this->load->view('footer');

				// }else{
				// 	$this->load->view('header');
				// 	$this->load->view('navigation',array('searchQuery'=>$q));
				// 	$this->load->view('user/resultPage',array("searchResults" => $data));
				// 	$this->load->view('footer');
				// }

			} catch(Exception $e) {
				$response->setSuccess(false);
				$response->setData(null);
				$response->setError(array(
					'code'=>'',
					'msg'=>$e->getMessage()
				));
			}
		}else{
			$response->setSuccess(false);
			$response->setData(null);
			$response->setError(array(
				'code'=>'',
				'msg'=>'Cross domain requests are not allowed'
			));
		}

		$response->respond();
		die();
	}

	function super_unique($array)
	{
		// $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

		$result = $array;
		foreach ($result as $key => $value){
		  	if ( is_array($value) )
		  	{
		   		$result[$key] = $this->super_unique($value);
		  	}
		}

		return $result;

	}

}
