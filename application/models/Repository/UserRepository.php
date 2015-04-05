<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity;

class UserRepository extends EntityRepository{
	public function authenticateUser($userName,$password){
		$em = $this->_em;
		// $user = $em->getRepository('Entity\User')->findOneBy(array('userName'=>$userName));

		$qb = $em -> createQueryBuilder();

		$dql = "SELECT u FROM Entity\User u WHERE (u.userName = :userName OR u.email = :userName)";
		$query = $em -> createQuery($dql);
		$query->setParameter('userName',$userName);

		$user = $query->getSingleResult();

		if($user){

			if($user->getPassword() == md5($password)){
				return $user;
			}else{
				return false;
			}

		}else{
			return false;
		}
	}

	public function checkUser($email){
		$em = $this->_em;
		$users = $em->getRepository('Entity\User')->findBy(array('email'=>$email));

		foreach ($users as $user) {

			if($user){
				return $user;
				die();
			} else{
				return false;
				die();
			}
		}
	}

	public function checkBannedStatus($email){
		$em = $this->_em;
		$user = $em->getRepository('Entity\User')->findOneBy(array('email'=>$email));

		if($user->getIsBannedStatus() == 0){
			return true;
		}else{
			return false;
		}
	}

	public function getUser($id)
	{
        $em = $this->_em;

		$user = $em->getRepository('Entity\User')->findOneBy(array("id" => $id));

		if(null != $user){
			return $user;	
		}else{
			return false;
		}
		
	}

	public function checkUserName($userInfo){
		$em = $this->_em;

		$userName =  $this->findOneBy(array('userName'=>$userInfo['reporter']));

		if($userInfo['id'] != 0){
			if(null != $userName && $userName->getId() != $userInfo['id']){
				return false;
			}else{
				return true;
			}
		}else{
			if(null != $userName){
				return false;
			}else{
				return true;
			}
		}
	}

}