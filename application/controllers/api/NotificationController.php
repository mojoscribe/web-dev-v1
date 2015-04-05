<?php 
class NotificationController extends CI_Controller {
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
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							$user = checkAuthToken();
 							if($_SERVER['REQUEST_METHOD'] == "POST"){
 								$notifications = $this->doctrine->em->getRepository('Entity\UserNotifications')->findBy(array('user'=>$user),array('id'=>'desc'),10);

 								
 								if(!is_null($notifications) && !empty($notifications) && "" != $notifications){
 									$data = array();

 									foreach ($notifications as $notification) {
 										$temp['id'] = $notification->getId();
 										$temp['notifyText'] = $notification->getNotifyText();
 										$temp['image'] = $notification->getImage();
 										$temp['action']['type'] = $notification->getActionType();
 										$temp['action']['id'] = $notification->getActionId();

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
 										'msg'=>'No Notifications to display'
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
 					'msg'=>'API key not set'
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

 	function paginate(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();

 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					if(isset($headers['auth-token'])){
 						if(checkAuthToken()){
 							$user = checkAuthToken();
 							if($_SERVER['REQUEST_METHOD'] == "POST"){
 								$notifications = $this->doctrine->em->getRepository('Entity\UserNotifications')->findBy(array('user'=>$user),array('id'=>'desc'),10);

 								if(!is_null($notifications) && !empty($notifications) && "" != $notifications){
 									$data = array();

 									$count = $_POST['count'];

 									for($i = $count; $i < ($count + 10); $i++) {

 										if($i == count($notifications)){
 											break;
 										}

 										$temp['id'] = $notifications[$i]->getId();
 										$temp['notifyText'] = $notifications[$i]->getNotifyText();
 										$temp['image'] = $notifications[$i]->getImage();
 										$temp['action']['type'] = $notifications[$i]->getActionType();
 										$temp['action']['id'] = $notifications[$i]->getActionId();

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
 										'msg'=>'No Notifications for you right now'
 									));
 								}
				 			}else{
				 				$response->setSuccess(false);
				 				$response->setData('');
				 				$response->setError(array(
				 					'code'=>'1100',
				 					'msg'=>'Method Error'
				 				));
				 			}
 						}else{
 							$response->setSuccess(false);
 							$response->setData('');
 							$response->setError(array(
 								'code'=>2002,
 								'msg'=>'User must be logged in.'
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
 						'code'=>'1099',
 						'msg'=>'Incorrect Api Key'
 					));
 				}
 			}else{
 				$response->setSuccess(false);
 				$response->setData('');
 				$response->setError(array(
 					'code'=>'1098',
 					'msg'=>'Api Key not set'
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