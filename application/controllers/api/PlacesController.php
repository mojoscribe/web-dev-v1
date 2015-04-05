<?php 
class PlacesController extends CI_Controller {
	var $cityRepository;
	var $countryRepository;
 	function __construct(){
 		parent::__construct();
 		$this->load->file('application/classes/Response.php');
 		$this->cityRepository = $this->doctrine->em->getRepository('Entity\City');
 		$this->countryRepository = $this->doctrine->em->getRepository('Entity\Country');
 		$this->load->helper('api');
 	}
 
 	function citySearch(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['q'])){
 								if(isset($_POST['country'])){
	 								$country = $this->doctrine->em->getRepository('Entity\Country')->findOneBy(array('name'=>$_POST['country']));

	 								if($country){
	 									$suggestions = $this->cityRepository->getSuggestions($_POST['q'],$_POST['country']);

	 									if(false != $suggestions){
	 										$data = array();
	 										foreach ($suggestions as $suggestion) {
	 											$temp['id'] = $suggestion->getId();
	 											$temp['name'] = $suggestion->getName();
	 											$temp['country'] = $suggestion->getCountry()->getName();

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
	 											'msg'=>'Your query did not return any results'
	 										));
	 									}
	 								}else{
	 									$response->setSuccess(false);
	 									$response->setData('');
	 									$response->setError(array(
	 										'code'=>'',
	 										'msg'=>'Country not found'
	 									));
	 								}
 								}else{
 									$response->setSuccess(false);
 									$response->setData('');
 									$response->setError(array(
 										'code'=>'',
 										'msg'=>'You have not set the country'
 									));
 								}
 							}else{
 								$response->setSuccess(false);
 								$response->setData('');
 								$response->setError(array(
 									'code'=>1100,
 									'msg'=>'Method Error'
								));
 							}
 						}else{
 							$response->setSuccess(false);
 							$response->setData('');
 							$response->setError(array(
 								'code'=>2002,
 								'msg'=>'Auth Token mismatch'
 							));
 						}
 					}else{
 						$response->setSuccess(false);
 						$response->setData('');
 						$response->setError(array(
 							'code'=>2005,
 							'msg'=>'Auth Token not set'
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
 					'msg'=>'API Key not set'
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

 	function countrySearch(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['q'])){

 								$suggestions = $this->countryRepository->getSuggestions($_POST['q']);

 								if(false != $suggestions){
 									$data = array();
									foreach ($suggestions as $suggestion) {
										$temp['id'] = $suggestion->getId();
										$temp['name'] = $suggestion->getName();

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
 										'msg'=>'Your query did not return any results'
 									));
 								}
 							}else{
 								$response->setSuccess(false);
 								$response->setData('');
 								$response->setError(array(
 									'code'=>1100,
 									'msg'=>'Method Error'
								));
 							}
 						}else{
 							$response->setSuccess(false);
 							$response->setData('');
 							$response->setError(array(
 								'code'=>2002,
 								'msg'=>'Auth Token mismatch'
 							));
 						}
 					}else{
 						$response->setSuccess(false);
 						$response->setData('');
 						$response->setError(array(
 							'code'=>2005,
 							'msg'=>'Auth Token not set'
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
 					'msg'=>'API Key not set'
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

 	function locationSearch(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							if($_SERVER['REQUEST_METHOD'] == "POST"){
 								if(isset($_POST['q'])){
									// $suggestions = $this->cityRepository->getLocations($_POST['q']);

									// if(false != $suggestions){
									// 	$data = array();
									// 	foreach ($suggestions as $suggestion) {
									// 		$temp['id'] = $suggestion->getId();
									// 		$temp['name'] = $suggestion->getName();

									// 		$data[] = $temp;
									// 	}

									// 	$response->setSuccess(true);
									// 	$response->setData($data);
									// 	$response->setError('');
									// }else{
									// 	$response->setSuccess(false);
									// 	$response->setData('');
									// 	$response->setError(array(
									// 		'code'=>'',
									// 		'msg'=>'Your query did not return any results'
									// 	));
									// }

 									$suggestions = file_get_contents("https://maps.googleapis.com/maps/api/place/autocomplete/json?key=AIzaSyAoRYcOug4BTqu9JfzVRpHf6ReQ7YBSE4Y&input=".$_POST['q']."&types=(cities)");

 									$suggestions = json_decode($suggestions,true);
 									// $suggestions = get_object_vars($suggestions);

 									$data = array();
 									if(!is_null($suggestions)){
	 									$i = 0;
	 									foreach ($suggestions['predictions'] as $suggestion) {
	 										$temp['id'] = $i;
	 										$temp['name'] = $suggestion['description'];
	 										
	 										$data[] = $temp;
	 										$i++;
	 									}

	 									$response->setSuccess(true);
	 									$response->setData($data);
	 									$response->setError('');
 									}else{
 										$response->setSuccess(false);
 										$response->setData('');
 										$response->setError(array(
 											'code'=>'',
 											'msg'=>'Your query did not return any results'
 										));
 									}
 								}else{
 									$response->setSuccess(false);
 									$response->setData('');
 									$response->setError(array(
 										'code'=>'',
 										'msg'=>'You have not set the query'
 									));
 								}
 							}else{
 								$response->setSuccess(false);
 								$response->setData('');
 								$response->setError(array(
 									'code'=>1100,
 									'msg'=>'Method Error'
 								));
 							}
 						}else{
 							$response->setSuccess(false);
 							$response->setData('');
 							$response->setError(array(
 								'code'=>2002,
 								'msg'=>'Auth Token mismatch'
 							));
 						}
 					}else{
 						$response->setSuccess(false);
 						$response->setData('');
 						$response->setError(array(
 							'code'=>2005,
 							'msg'=>'Auth Token not set'
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
 					'msg'=>'API Key not set'
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
