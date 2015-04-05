<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Entity;

class CountryRepository extends EntityRepository {
	public function getSuggestions($q){
		$em = $this->_em;
		try {
			$qb = $em->createQueryBuilder();
		
			$dql = "SELECT c FROM Entity\Country c WHERE c.name LIKE :keyword";
			$query = $em->createQuery($dql);
			$query->setParameter('keyword','%'.$q.'%');
			$query->setMaxResults(10);

			$suggestions = $query->getResult();

			if(!is_null($suggestions)){
				return $suggestions;
			}else{
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}	
}