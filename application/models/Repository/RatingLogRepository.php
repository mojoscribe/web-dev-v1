<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity;

class RatingLogRepository extends EntityRepository{

	public function checkRecord($user,$post){
		try {
			
	    	$em = $this->_em;

		    $userRating = $this->findOneBy(array('user' => $user,'post' => $post));

		    if(!empty($userRating)){
			  	$rating = null;
			   	if(null != $userRating->getRating()){
				   	$rating = $userRating->getRating();
			   	}else{
			   		$rating = 0;
			   	}
			   	return $rating;
			}else {
			   return false;
			
			}
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}

	public function checkLogForUser($user,$post){
		try {
			$em = $this->_em;

			$userRating = $this->findOneBy(array('user'=>$user,'post'=>$post));

			if(null != $userRating){
				return false;
			}else{
				return true;
			}

		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}
}