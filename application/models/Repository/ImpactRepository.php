<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity;

class ImpactRepository extends EntityRepository{
	public function findImpact($impac){
		$em = $this->_em;

		$impact = $em->getRepository('Entity\Impact')->findOneBy(array('id'=>$impac));

		if($impact){
			return $impact;
		}else{
			return false;
		}
	}
}