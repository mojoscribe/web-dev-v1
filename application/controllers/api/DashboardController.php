<?php 
class DashboardController extends CI_Controller {
 	var $postRepository;
 	function __construct(){
 		parent::__construct();
 		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
 		$this->load->file('application/classes/Response.php');
 		$this->load->helper('api');
 	}
 
 	function index(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(false != checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							$user = checkAuthToken();
	 						$featuredPosts = $this->postRepository->getBreakingNewsPosts();

	 						$featuredData = array();

	 						$count = count($featuredPosts);
	 						if($count >=6){
	 							$count = 6;
	 						}


	 						if(!is_null($featuredPosts) && !empty($featuredPosts)){
		 						for ($i=0; $i < $count; $i++) {
		 							$temp['id'] = $featuredPosts[$i]->getId();
		 							$temp['author'] = $featuredPosts[$i]->getAuthor()->getUserName();
		 							$temp['headline'] = $featuredPosts[$i]->getHeadline();

		 							if(!is_null($featuredPosts[$i]->getPostDetails())){
		 								if(null != $featuredPosts[$i]->getPostDetails()->getRating()){
			 								$temp['rating'] = round($featuredPosts[$i]->getPostDetails()->getRating(),5);
		 								}else{
		 									$temp['rating'] = 0;
		 								}

		 								$temp['views'] = $featuredPosts[$i]->getPostDetails()->getNumberOfViews();
		 							}else{
		 								$temp['rating'] = 0;
		 								$temp['views'] = "0";
		 							}

		 							if(!is_null($featuredPosts[$i]->getSharedCount())){
										$temp['numberOfShares'] = $featuredPosts[$i]->getSharedCount();								
									}else{
										$temp['numberOfShares'] = "0";
									}

									$temp['type'] = $featuredPosts[$i]->getPostType();

									$temp['impact'] = $featuredPosts[$i]->getUserImpact()->getArea();

		 							$temp['files'] = array();
		 							foreach ($featuredPosts[$i]->getFiles() as $file) {
		 								$temp2['id'] = $file->getId();
		 								if($featuredPosts[$i]->getPostType() == "Image"){
			 								$temp2['file'] = base_url().$file->getBig();
		 								}else{
		 									$temp2['file'] = base_url().$file->getBig();
		 								}

		 								$temp['files'][] = $temp2;
		 							}

		 							$featuredData[] = $temp;
		 						}
		 					}

	 						if(!is_null($user->getUser_categoryPreference())){
	 							$preferenceData = array();
		 						foreach ($user->getUser_categoryPreference() as $preference) {
		 							$preferredPosts = $this->postRepository->getPostsByCategory($preference);

		 							$data = array();

		 							$count2 = count($preferredPosts);

		 							if($count2 >=6){
		 								$count2 = 6;
		 							}

		 							if(null != ($preferredPosts)){
			 							for ($i=0; $i < $count2; $i++) { 
			 								$temp['id'] = $preferredPosts[$i]->getId();
			 								$temp['headline'] = $preferredPosts[$i]->getHeadline();
			 								$temp['files'] = array();

			 								foreach ($preferredPosts[$i]->getFiles() as $file) {
			 									$tmp['id'] = $file->getId();
			 									$tmp['file'] = base_url().$file->getDeviceImage();

			 									$temp['files'][] = $tmp;
			 								}

			 								$temp['type'] = $preferredPosts[$i]->getPostType();

			 								$data['id'] = $preference->getId();
			 								$data['name'] = $preference->getName();
			 								$data['posts'][] = $temp;
			 							}
			 							$preferenceData[] = $data;
		 							}

		 							
		 						}

		 						$response->setSuccess(true);
		 						$response->setData(
		 							array('featured'=>$featuredData,'preferredPosts'=>
		 							$preferenceData));
		 						$response->setError('');
	 						}
	 					}else{
	 						$response->setSuccess(false);
	 						$response->setData('');
	 						$response->setError(array(
	 							'code'=>2002,
	 							'msg'=>'Auth token mismatch'
	 						));
	 					}
 					}else{
 						$response->setSuccess(false);
 						$response->setData('');
 						$response->setError(array(
 							'code'=>2005,
 							'msg'=>'Auth token not set'
 						));
 					}
 				}else{
 					$response->setSuccess(false);
 					$response->setData('');
 					$response->setError(array(
 						'code'=>1099,
 						'msg'=>'Invalid API Key'
 					));
 				}
 			}else{
 				$response->setSuccess(false);
 				$response->setData('');
 				$response->setError(array(
 					'code'=>1098,
 					'msg'=>'Api key not set'
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
 } ?>
