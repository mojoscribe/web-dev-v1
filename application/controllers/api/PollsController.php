<?php 
use Entity\UserResponse;
class PollsController extends CI_Controller {
 	var $pollRepository;
 	function __construct(){
 		parent::__construct();
 		$this->pollRepository = $this->doctrine->em->getRepository('Entity\Poll');
 		$this->load->file('application/classes/Response.php');
 		$this->load->helper('api');
 	}
 
 	function index(){
 		$response = new Response();
 		try {
 			$headers = apache_request_headers();
 			if(isset($headers['api-key'])){
 				if(checkApiKey()){
 					
					if($_SERVER['REQUEST_METHOD'] == "POST"){
						$polls = $this->doctrine->em->getRepository('Entity\Poll')->findBy(array(),array('id'=>'desc'));
						
						if(null != $polls){
							$data = array();

							foreach ($polls as $poll) {
								$temp['id'] = $poll->getId();
								$temp['question'] = $poll->getPollContent();
								$answers = $poll->getOptionText();
								 
								$options = array();
								foreach ($answers as $option) {
									$opt = array();
									$opt['id'] = $option->getId();
									$opt['answer'] = $option->getOptionText();
									$options[] = $opt;
								}
								//$temp['answers'] = $options;
								$temp['results'] = $this->calculateResults($poll->getId());
								
								if(isset($headers['auth-token']) && false != checkAuthToken()) {
									$user = checkAuthToken();
									$userResponse = $this->doctrine->em->getRepository('Entity\UserResponse')->findOneBy(array('user' => $user, 'poll' => $poll));
									if(!is_null($userResponse)) {
										$temp['isAnswered'] = true;
									} else {
										$temp['isAnswered'] = false;
									}
								} else {
									$temp['isAnswered'] = false;
								}

								$data[] = $temp;
							}

							$response->setSuccess(true);
							$response->setData($data);
							$response->setError('');

						}else{
							$response->setSuccess(false);
							$response->setData('');
							$response->setError(array('msg'=>'No Polls'));
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
 						'code'=>1099,
 						'msg'=>'Invalid API key'
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


 	private function calculateResults($pollId) {
		// $pollId = 1;

		$poll = $this->doctrine->em->getRepository('Entity\Poll')->findOneBy(array('id' => $pollId));
		$options = $this->doctrine->em->getRepository('Entity\PollOptions')->findBy(array('poll' => $poll));
		$responses = $this->doctrine->em->getRepository('Entity\UserResponse')->findBy(array('poll' => $poll));

		$answers = array();
		

		//Initialize array element for each element
		foreach($options as $option) {
			$answers[$option->getId()] = 0;
		}

		
		foreach($responses as $response) {
			$temp = array();

			if(!isset($answers[$response->getOptionText()->getId()]['count'])) {	
				$temp['id'] = $response->getOptionText()->getId();
				$temp['option'] = $response->getOptionText()->getOptionText();
				$temp['count'] = 1;
			} else {
				$temp = $answers[$response->getOptionText()->getId()];
				$temp['count'] += 1;
			}
			$answers[$response->getOptionText()->getId()] = $temp;
		}


		//add missing options to answers and calculate percentage

		$totalResponses = count($responses);
		$final = array();
		$i = 0;

		foreach($answers as $key => $answer) {


			if(0 == $answer) {
				$answer = array();
				$answer['id'] = $options[$i]->getId();
				$answer['option'] = $options[$i]->getOptionText();
				$answer['count'] = 0;
			}
			
			if($totalResponses > 0) {
				$answer['percentage'] = ($answer['count'] / $totalResponses) * 100;	
			} else {
				$answer['percentage'] = 0;
			}
			
			$final[] = $answer;
			$i++;
		}

		return $final;
	}

	function submitPoll(){
		$response = new Response();
		try {
			$headers = apache_request_headers();
			if(isset($headers['api-key'])){
				if(checkApiKey()){
					if(isset($headers['auth-token'])){
						if(checkAuthToken()){
							$user = checkAuthToken();
							if($_SERVER['REQUEST_METHOD'] == "POST"){
								if(isset($_POST['pollId']) && isset($_POST['optionId'])){
									$pollId = $_POST['pollId'];
									$optionId = $_POST['optionId'];


									$option = $this->doctrine->em->getRepository('Entity\PollOptions')->findOneBy(array('id' => $optionId));
									$poll = $this->doctrine->em->getRepository('Entity\Poll')->findOneBy(array('id' => $pollId));
						
									$userR = $this->doctrine->em->getRepository('Entity\UserResponse')->findOneBy(array('poll'=>$poll,'user'=>$user));

									// echo "<pre>";
									// print_r($userR);
									// die();

									if(empty($userR) && "" == $userR){
										$userResponse = new UserResponse();
										$userResponse->setUser($user);
										$userResponse->setPoll($poll);
										$userResponse->setOptionText($option);
										$this->doctrine->em->persist($userResponse);
										$this->doctrine->em->flush();

										$response->setSuccess(true);
										$response->setData(array('results' => $this->calculateResults($pollId)));
										$response->setError("");
									}else{
										$response->setSuccess(false);
										$response->setData('');
										$response->setError(array(
											'code'=>'',
											'msg'=>'User has already answered the poll'
										));
									}
								}else{
									$response->setSuccess(false);
									$response->setData('');
									$response->setError(array(
										'code'=>'',
										'msg'=>'Poll Id not set'
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
								'msg'=>'Login to answer this poll'
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
						'msg'=>'Invalid Api Key'
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