<?php 
use Entity\UserResponse;
class PollController extends CI_Controller {
	var $pollRepository;
	function __construct(){
		parent::__construct();
		$this->load->helper('angular');
		$this->load->file('application/classes/Response.php');
		$pollRepository = $this->doctrine->em->getRepository('Entity\Poll');
	}

	function index(){
		try {
			if($_SERVER['REQUEST_METHOD'] == "GET"){

				if(false != isUserLoggedIn()){
					$user = isUserLoggedIn();

					$profile['id'] = $user->getId();
					$profile['picture'] = $user->getProfilePicturePath();
					$profile['userName'] = $user->getUserName();

			 		$this->load->view('header',array('data'=>$profile));
			 		// $this->load->view('user/header');
			 		$this->load->view('navigation');
			 		$this->load->view('polls');
			 		$this->load->view('footer',array('scripts'=>array('controllers/pollpagecontroller.js')));

				}else{

			 		$this->load->view('header');
			 		$this->load->view('navigation');
			 		$this->load->view('polls');
			 		$this->load->view('footer',array('scripts'=>array('controllers/pollpagecontroller.js')));
				}
			}else{
				redirect('/');
			}
		} catch (Exception $e) {
			redirect('technicalProblem');
		}
	}

	function getPolls(){
		$response = new Response();
		if(false != checkCsrf()){
			try {
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
							$temp['answers'] = $options;
							$temp['results'] = $this->calculateResults($poll->getId());
							
							if($user = isUserLoggedIn()) {
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
					$response->setError(array('msg'=>'Method Error'));
				}
			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>$e->getMessage()));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>'Cross domain requests are not allowed'));
		}

		$response->respond();
		die();
	}

	function getLatestJSON(){

		$latest = $this->doctrine->em->getRepository('Entity\Poll')->getLatest();

		$response = new Response();

		if($latest) {
			if($user = isUserLoggedIn()) {

				$userResponse = $this->doctrine->em->getRepository('Entity\UserResponse')->findOneBy(array('user' => $user, 'poll' => $latest));
				if(!is_null($userResponse)) {
					
					$poll = array();
					$poll['id'] = $latest->getId();
					$poll['question'] = $latest->getPollContent();
					$answers = $latest->getOptionText();
					 
					$options = array();
					foreach ($answers as $option) {
						$opt = array();
						$opt['id'] = $option->getId();
						$opt['answer'] = $option->getOptionText();
						$options[] = $opt;
					}
					$poll['answers'] = $options;

					$response->setSuccess(true);
					$response->setData(array(
						'isAnswered' => true,
						'poll' => $poll, 
						'results' => $this->calculateResults($latest->getId())));
					$response->setError("");
					$response->respond();
					die();	
				} 
			}

			$poll = array();
			$poll['id'] = $latest->getId();
			$poll['question'] = $latest->getPollContent();
			$answers = $latest->getOptionText();
			 
			$options = array();
			foreach ($answers as $option) {
				$opt = array();
				$opt['id'] = $option->getId();
				$opt['answer'] = $option->getOptionText();
				$options[] = $opt;
			}
			$poll['answers'] = $options;
			$response->setSuccess(true);
			$response->setError('');
			$response->setData($poll);
			
			
		} else {
			$response->setSuccess(false);
			$reponse->setError(array(
					'code' => 404,
					'msg' => "No Poll Found"
				));
			$response->setData("");
		}

		$response->respond();
		die();
	}

	//AJAX Post
	function submit(){

		$response = new Response();
		$_POST = getPostData();
		if(true){ // TODO: Do Security and CSRF checks
			if($user = isUserLoggedIn()) {
				// echo "<pre>";
				// print_r($_POST);
				// die();

				$pollId = $_POST['pollid'];
				$optionId = $_POST['option'];
				
				try {

					$option = $this->doctrine->em->getRepository('Entity\PollOptions')->findOneBy(array('id' => $optionId));
					$poll = $this->doctrine->em->getRepository('Entity\Poll')->findOneBy(array('id' => $pollId));
					
					$userResponse = new UserResponse();
					$userResponse->setUser($user);
					$userResponse->setPoll($poll);
					$userResponse->setOptionText($option);
					$this->doctrine->em->persist($userResponse);
					$this->doctrine->em->flush();

					$response->setSuccess(true);
					$response->setData(array('results' => $this->calculateResults($pollId)));
					$response->setError("");
				} catch (Exception $e) {
					$response->setSuccess(false);
					$response->setData("");
					$response->setError(array(
							'code' => 500,
							'msg' => $e->getMessage()
						));
				}
			} else {
				$response->setSuccess(false);
				$response->setData("");
				$response->setError(array(
						'code' => 2005,
						'msg' => "You have to be logged in to submit a poll"
					));
			}		
		} else {
			$response->setSuccess(false);
			$response->setData("");
			$response->setError(array(
					'code' => 1001,
					'msg' => "Cross domain requests are not allowed",
				));
		}
		$response->respond();
		die();
	}

	function getResults(){

		$response = new Response();
		$_POST = getPostData();
		if(true){ // TODO: Do Security and CSRF checks
			if($user = isUserLoggedIn()) {
				$pollId = $_POST['pollid'];				
				
				try {
					$results = $this->calculateResults($pollId);
					$response->setSuccess(true);
					$response->setData(array('results' => $results));
					$response->setError("");
				} catch (Exception $e) {
					$response->setSuccess(false);
					$response->setData("");
					$response->setError(array(
							'code' => 500,
							'msg' => $e->getMessage()
						));
				}
			} else {
				$response->setSuccess(false);
				$response->setData("");
				$response->setError(array(
						'code' => 2005,
						'msg' => "You have to be logged in to see the answers"
					));
			}		
		} else {
			$response->setSuccess(false);
			$response->setData("");
			$response->setError(array(
					'code' => 1001,
					'msg' => "Cross domain requests are not allowed",
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

	function getAll(){
		$response = new Response();
		$_POST = getPostData();
		if(true){ // TODO: Do Security and CSRF checks

		} else {
			$response->setSuccess(false);
			$response->setData("");
			$response->setError(array(
					'code' => 1001,
					'msg' => "Cross domain requests are not allowed",
				));
		}
	}

}
