<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Entity;

class PollRepository extends EntityRepository {
	
	function getLatest(){
		// $poll = $this->findOneBy(array('id' => 1));

		$em = $this->_em;
		$limit = 1;

		$q = "SELECT p FROM Entity\Poll p ORDER BY p.id DESC";
				
		$query = $em->createQuery($q);
		$query->setMaxResults($limit);

		$poll = $query->getSingleResult();

		return $poll;
	}
}