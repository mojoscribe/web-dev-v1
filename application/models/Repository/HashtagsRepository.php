<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity;

class HashtagsRepository extends EntityRepository{
	public function checkHashtag($hash){
		$em = $this->_em;

		$hashtag = $em->getRepository('Entity\Hashtags')->findOneBy(array('hashtag'=>$hash));

		if($hashtag){
			return $hashtag;
		}else{
			return false;
		}
	}

	public function getTopHashtagsInPastDays($pastDate,$date){
		$em = $this->_em;

		$limit = 20;

		$dql = "SELECT h FROM Entity\Hashtags h WHERE DATEDIFF(:date, h.updatedOn) < 20 ORDER BY h.hashtagUseCount DESC";
		$q = $em->createQuery($dql)
			->setParameter('date', $date->format('Y-m-d'))
			->setMaxResults($limit);

		$hashtags = $q->getResult();

		if(null != $hashtags){
			$data = array();
			foreach ($hashtags as $hashtag) {
				$data[] = $hashtag->getHashtag();
			}
			return $data;
		}else{
			return false;
		}
	}

}