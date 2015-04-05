<?php
use Entity\Poll;
use Entity\PollOptions;
class PollController extends CI_Controller {

	function __construct() {
		parent::__construct();

	}

	function index() {
		try {
			$this -> checkSession();
			$polls = $this -> doctrine -> em -> getRepository('Entity\Poll') -> findAll();
			// print_r($polls);
			$this -> load -> view("admin/header");
			$this -> load -> view("admin/poll/list", array("polls" => $polls));
			$this -> load -> view("admin/footer");
		} catch(Exception $e) {
			print_r($e -> getMessage());
			// redirect("/error");
		}
	}

	function add() {
		try {
			$id = $this -> checkSession();
			$poll = new Poll();
			if ($_SERVER['REQUEST_METHOD'] == "GET") {
				$this -> load -> view("admin/header");
				$this -> load -> view("admin/poll/add");
				$this -> load -> view("admin/footer");
			} else {
				$author = $this -> doctrine -> em -> getRepository('Entity\User') -> findOneBy(array("id" => $id));

				$poll -> setAuthor($author);

				$poll -> setPollTitle($_POST['questionTitle']);

				$poll -> setPollContent($_POST['questionDesc']);
				$this -> doctrine -> em -> persist($poll);
				//Add first option
				$option = $this -> insertOption($_POST['Option1'], $poll);

				$poll -> addOptionText($option);

				//Add second option
				$option = $this -> insertOption($_POST['Option2'], $poll);
				$poll -> addOptionText($option);

				//add 3rd option
				if ($_POST['Option3']) {
					$option = $this -> insertOption($_POST['Option3'], $poll);
					$poll -> addOptionText($option);
				}
				//add 4th option
				if ($_POST['Option4']) {
					$option = $this -> insertOption($_POST['Option4'], $poll);
					$poll -> addOptionText($option);
				}

				$slug = $this -> create_slug($_POST['questionTitle']);
				$poll -> setSlug($slug);
				$this -> doctrine -> em -> persist($poll);
				$this -> doctrine -> em -> flush();

				redirect('admin/poll');
			}

		} catch(Exception $e) {
			print_r($e -> getMessage());
			redirect("error");
		}
	}
function edit() {
		try {
			$id = $this -> checkSession();
			// print_r('here');
			$pollId = $_GET['id'];
			$poll = $this->doctrine->em->getRepository('Entity\Poll')->findOneBy(array("id"=>$pollId));

			if ($_SERVER['REQUEST_METHOD'] == "GET") {
				$this -> load -> view("admin/header");
				$this -> load -> view("admin/poll/add",array("poll"=>$poll));
				$this -> load -> view("admin/footer");
			} else {

				$author = $this -> doctrine -> em -> getRepository('Entity\User') -> findOneBy(array("id" => $id));

				$poll -> setAuthor($author);

				$poll -> setPollTitle($_POST['questionTitle']);

				$poll -> setPollContent($_POST['questionDesc']);
				$this -> doctrine -> em -> persist($poll);
				//Add first option
				$option = $this -> insertOption($_POST['Option1'], $poll, $_POST['OptionId1']);

				$poll -> addOptionText($option);

				//Add second option
				$option = $this -> insertOption($_POST['Option2'], $poll, $_POST['OptionId2']);
				$poll -> addOptionText($option);

				//add 3rd option
				if ($_POST['Option3']) {
					$option = $this -> insertOption($_POST['Option3'], $poll, $_POST['OptionId3']);
					$poll -> addOptionText($option);
				}
				//add 4th option
				if ($_POST['Option4']) {
					$option = $this -> insertOption($_POST['Option4'], $poll, $_POST['OptionId4']);
					$poll -> addOptionText($option);
				}

				$slug = $this -> create_slug($_POST['questionTitle']);
				$poll -> setSlug($slug);
				$this -> doctrine -> em -> persist($poll);
				$this -> doctrine -> em -> flush();

				redirect('admin/poll');
			}

		} catch(Exception $e) {
			print_r($e -> getMessage());
			redirect("error");
		}
	}

	function create_slug($string) {
		try {
			$string = strtolower($string);
			$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
			return $slug;
		} catch(Exception $e) {
			$response -> setSuccess(false);
			$response -> setError(array('msg' => $e -> getMessage()));
			print_r($e);
			redirect('error');
		}
	}

	function insertOption($recOption, $poll ,$id=0) {
		try {
			print_r($id);
            if($id == 0 )
			{
				$option = new PollOptions();
			}else
				{
                $option = $this->doctrine->em->getRepository('Entity\PollOptions')->findOneBy(array("id"=>$id));
				}
			$option -> setOptionText($recOption);
			$option -> setPoll($poll);
			$this -> doctrine -> em -> persist($option);
			// $this->doctrine->em->flush();
			return $option;
		} catch(Exception $e) {
			print_r($e -> getMessage());

		}
	}

	function delete()
	{
       try{
           $this->checkSession();
           $id = $_GET['id'];
           $poll = $this->doctrine->em->getRepository('Entity\Poll')->findOneBy(array("id"=>$id));
		   $pollOptions = $this->doctrine->em->getRepository('Entity\PollOptions')->findBy(array("poll" => $poll));
		   foreach ($pollOptions as $key => $value) {
           $this->doctrine->em->remove($value);
		   }
		   $this->doctrine->em->remove($poll);
		   $this->doctrine->em->flush();
          redirect("admin/poll");
       }
	   catch(Exception $e)
	   {
        print_r($e->getMessage());
		// redirect("error");
	   }
	}

	function checkSession() {
		try {
			session_start();
			if (isset($_SESSION['adminUserId'])) {
				return $_SESSION['adminUserId'];
			} else {
				redirect('admin');
			}
		} catch ( Exception $e ) {
			print_r($e -> getMessage());
			redirect("/error");
		}
	}

}
