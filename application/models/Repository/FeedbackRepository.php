<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Entity;
use Entity\Feedback;

class FeedbackRepository extends EntityRepository {
	/*Inserts the feedback record*/
	public function addFeedback($email, $content) {
		try {
			$em = $this -> _em;
				$feedback = new Entity\Feedback();
				$feedback -> setUser($email);
				$feedback -> setContent($content);


			$em -> persist($feedback);
			$em -> flush();
			return true;
		} catch(Exception $e) {
			print_r($e -> getMessage());
			redirect('error');
		}
	}

}