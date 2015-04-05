<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity;

class PostDetailsRepository extends EntityRepository{
	public function getPostDetails($post){
		$em = $this->_em;

		$postDetails = $this->findOneBy(array('post'=>$post));

		if(null != $postDetails){
			return $postDetails;
		}else{
			return false;
		}
	}
}