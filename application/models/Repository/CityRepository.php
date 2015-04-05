<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Entity;

class CityRepository extends EntityRepository {
	public function getSuggestions($q,$country){
		$em = $this->_em;
		try {
			$qb = $em->createQueryBuilder();
		
			$dql = "SELECT c FROM Entity\City c LEFT JOIN c.country co  WHERE co.name = :country AND c.name LIKE :keyword";
			$query = $em->createQuery($dql);
			$query->setParameter('keyword','%'.$q.'%');
			$query->setParameter('country',$country);
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

	public function getLocations($q){
		$em = $this->_em;
		try {
			$qb = $em->createQueryBuilder();
		
			$dql = "SELECT c FROM Entity\City c WHERE c.name LIKE :keyword";
			$query = $em->createQuery($dql);
			$query->setParameter('keyword',$q.'%');
			// $query->setMaxResults(20);

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