<?php 
class TrendingHashController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->load->helper('api');
 	}
 
 	function index(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					$trendingHashtags = $this->doctrine->em->getRepository('Entity\TrendingHashtags')->findBy(array(),array('id'=>'desc'),10);

 					if(!is_null($trendingHashtags) && !empty($trendingHashtags)){
	 					$data = array();

	 					foreach ($trendingHashtags as $hashtag) {
	 						$temp['id'] = $hashtag->getId();
	 						$temp['hashtag'] = $hashtag->getName();
	 						$temp['rank'] = $hashtag->getHashtagRank();

	 						$data[] = $temp;
	 					}

	 					$response->setSuccess(true);
	 					$response->setData($data);
	 					$response->setError('');
 					}else{
 						$response->setSuccess(false);
 						$response->setData('');
 						$response->setError(array(
 							'code'=>'',
 							'msg'=>'No Hashtags'
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
 					'msg'=>'API Key is not set'
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