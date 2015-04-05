<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Entity;
use Entity\Follow;

class FollowRepository extends EntityRepository {

	public function getFollow($user, $author) {
		try {
			$em = $this -> _em;
			$follow = $this -> findOneBy(array("user" => $user, "author" => $author));
			if (!empty($follow)) {
				return $follow;
			} else {
				return FALSE;
			}
		} catch(Exception $e) {
			print_r($e -> getMessage());
		}
	}

	public function getSubscribedUser($user){
		$em = $this->_em;

		$userSubscriptions = $em->getRepository('Entity\Follow')->findBy(array('user'=>$user));

		if(null != $userSubscriptions){
			return $userSubscriptions;
		}else{
			return false;
		}
	}
}