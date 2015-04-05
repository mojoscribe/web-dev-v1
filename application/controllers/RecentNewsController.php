<?php 
class RecentNewsController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
	}

	function index(){
		try {
			if(false != isUserLoggedIn()){

				$user = isUserLoggedIn();

				$profile['userName'] = $user->getUserName();
				$profile['picture'] = $user->getProfilePicturePath();

				$this->load->view('header',array('data'=>$profile));
				$this->load->view('navigation');
				$this->load->view('pages/recent');
				$this->load->view('footer',array('scripts'=>array('controllers/recentNewsPageController.js')));

			}else{
				$this->load->view('header');
				$this->load->view('navigation');
				$this->load->view('pages/recent');
				$this->load->view('footer',array('scripts'=>array('controllers/recentNewsPageController.js')));
			}
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}

	function getRecentNewsPosts(){
		$response = new Response();
		try {
			$data = array();

			$recentNewsPosts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('postStatus'=>array('PUBLISHED')),array('updatedOn'=>'desc'),25);

			if(null != $recentNewsPosts){
				foreach ($recentNewsPosts as $recent) {
					$temp['id'] = $recent->getId();
					$temp['title'] = $recent->getHeadline();
					$temp['description'] = $recent->getDescription();
					$date = $recent->getUpdatedOn();
					$date = $date->format('d-M-Y H:i:s');
					$temp['date'] = $date;

					if($recent->getIsAnonymous() == true){
						$temp['author'] = "Anonymous";	
					}else{
						$temp['author'] = $recent->getAuthor()->getUserName();
					}
					
					$temp['hashtags'] = array();

					foreach ($recent->getHashtags() as $hashtag) {
						$temp['hashtags'][] = $hashtag->getHashtag();
					}

					$temp['files'] = array();

					foreach ($recent->getFiles() as $file) {
						$temp['files'] = $file->getThumb();
						break;
					}
					$temp['slug'] = $recent->getSlug();

					if($recent->getPostType() == "Video"){
						$temp['showVideo'] = true;
					}else{
						$temp['showVideo'] = false;
					}

					$data[] = $temp;
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
					'msg'=>'No posts to display'
				));
			}
		} catch (Exception $e) {
			$response->setSuccess(false);
			$response->setData(null);
			$response->setError(array(
				'code'=>'',
				'msg'=>$e->getMessage()
			));			
		}

		$response->respond();
		die();
	}
}
 ?>