<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity;

class CategoryRepository extends EntityRepository{
	
	public function getCategory($categ){
		$em = $this->_em;

		$category = $em->getRepository('Entity\Category')->findOneBy(array('id'=>$categ));

		if($category){
			return $category;
		}else{
			return false;
		}
	}
}