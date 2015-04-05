<?php
use Entity\Feedback;

class FeedbackController extends CI_Controller {
	var $feedbackRepository;
	function __construct() {
		parent::__construct();
		$this -> feedbackRepository = $this -> doctrine -> em -> getRepository('Entity\Feedback');

	}
	function index() {
		try {
			$this -> load -> view('header');
			$this -> load -> view('navigation');
			if ($_SERVER['REQUEST_METHOD'] == "GET") {
				$this -> load -> view('feedback');
			} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
				$this -> feedbackRepository -> addFeedback($_POST['email'], $_POST['content']);
				$message = "Thank you for sharing your thoughts.We will get back to you shortly.";
				$this -> load -> view('feedback', array("message" => $message));
			}
			$this -> load -> view('footer');
		} catch(Exception $e) {
			redirect('technicalProblem');
		}
	}
}
