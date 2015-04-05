<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity;

class PostRepository extends EntityRepository {
	public function getPost($pid) {
		try {
			$em = $this -> _em;

			$post = $em -> getRepository('Entity\Post') -> findOneBy(array('id' => $pid));

			if (null != $post) {
				return $post;
			} else {
				return false;
			}
		} catch (Exception $e) {

		}
	}

	public function getAllDrafts($user) {
		$em = $this -> _em;

		$draft = "DRAFT";
		$uEmail = $user -> getEmail();

		$qb = $em -> createQueryBuilder();

		$dql = "SELECT p FROM Entity\Post p JOIN p.author a WHERE a.email = :uemail AND (p.postStatus = :draft) ORDER BY p.id DESC";
		$query = $em -> createQuery($dql);
		$query -> setParameter('draft', $draft);
		$query -> setParameter('uemail', $uEmail);

		$drafts = $query -> getResult();

		if ($drafts) {
			return $drafts;
		} else {
			return false;
		}
	}

	public function getAllPostsForUser($user) {
		$em = $this ->_em;

		$publish = "PUBLISHED";
		$uEmail = $user -> getEmail();

		$qb = $em -> createQueryBuilder();

		$dql = "SELECT p FROM Entity\Post p JOIN p.author a WHERE a.email = :uemail AND p.postStatus = :publish AND p.isAnonymous = :f  ORDER BY p.postRanking DESC";
		$query = $em -> createQuery($dql);
		$query -> setParameter('publish', $publish);
		$query -> setParameter('uemail', $uEmail);
		$query->setParameter('f',false);

		$posts = $query -> getResult();

		if (null != $posts) {
			return $posts;
		} else {
			return false;
		}
	}

	public function getDraft($id) {
		$em = $this -> _em;

		$drafts = $em -> getRepository('Entity\Post') -> findBy(array('id' => $id));

		if (null != $drafts) {
			return $drafts;
		} else {
			return false;
		}
	}

	public function getAllRecords($count=0) {

		$em = $this -> _em;
		if($count == 0) {
			$dql = "SELECT p FROM Entity\Post p WHERE p.postStatus = :publish ORDER BY p.postRanking DESC";
			$q = $em->createQuery($dql)
					->setParameter('publish', "PUBLISHED");

			$posts = $q->getResult();
		} else {
			$dql = "SELECT p FROM Entity\Post p WHERE p.postStatus = :publish ORDER BY p.postRanking DESC";
			$q = $em->createQuery($dql)
					->setParameter('publish', "PUBLISHED")
					->setMaxResults($count);

			$posts = $q->getResult();
		}
		if(null != $posts){
			return $posts;	
		}else{
			return false;
		}
	}

	public function getPublishedPosts(){
		$em = $this -> _em;

		$posts = $this -> findBy(array('postStatus' => array('PUBLISHED'),'author' => !null),array('postRanking'=>'desc'));

		if(null != $posts){
			return $posts;	
		}else{
			return false;
		}
	}

	public function getBreakingNewsPosts(){
		$em = $this->_em;

		$posts = $this->findBy(array('postStatus'=>'PUBLISHED','isBreaking'=>true),array('postRanking'=>'desc'));

		if(null != $posts){
			return $posts;
		}else{
			return false;
		}
	}

	public function getRecentNewsPosts(){
		$em = $this->_em;

		$posts = $this->findBy(array('postStatus'=>'PUBLISHED'),array('id'=>'desc'));

		if(null != $posts){
			return $posts;
		}else{
			return false;
		}
	}

	public function getPostDetails() {
		/*get data required on landing page in a single array* */
		$em = $this -> _em;
		$data = array();
		$tmp = array();
		$post = $em -> getRepository('Entity\Post') -> findAll(array('id' => 'desc'));
		//gather all the necessary data in single array

		$i = 0;
		foreach ($post as $value) {
			// landing page needs 5 latest posts details
			if ($i < 5) {
				$image = $em -> getRepository('Entity\File') -> findOneBy(array('post' => $value));
				if ($image) {
					$tmp['image'] = $image -> getFilepath();
				}
				 $tmp['headline'] = $value -> getHeadline();
				   $tmp['author'] = $value -> getAuthor() -> getFirstName() . " " . $value -> getAuthor() -> getLastName();
				  $tmp['content'] = $value -> getDescription();
				     $tmp['slug'] = $value -> getSlug();
				          $data[] = $tmp;
				             $tmp = array();
				$i++;
			}
		}
		return $data;
	}

	public function getPostsByHashtag($hashtag){
		$em = $this->_em;

		$h = $em->getRepository('Entity\Hashtags')->findOneBy(array('hashtag'=>$hashtag));

		$dql = "SELECT p FROM Entity\Post p WHERE :tag IN ('asdf', 'tag3')";
		$query = $em -> createQuery($dql);
		$query -> setParameter('tag', $hashtag);

		$hashs = $query->getResult();

		echo "<pre>";
		print_r(count($hashs));
		die();

	}

	public function createSlug($string) {
		try {
			$string = strtolower($string);
			$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
			return $slug;
		} catch(Exception $e) {
			$response -> setSuccess(false);
			$response -> setError(array('msg' => $e -> getMessage()));
			print_r($e);
			redirect('error');
		}
	}

	// public function createDiffentSlug($string){
	// 	try {
	// 		$string = strtolower($string);
	// 	} catch (Exception $e) {
			
	// 	}		
	// }
//retreives post details according to post
	public function getPostDetailsRecord($post)
	{
           try{
		   $em = $this->_em;
		   $postDetails = $em->getRepository('Entity\PostDetails')->findOneBy(array("post" => $post));
		   if(!empty($postDetails))
           {
           	return $postDetails;
		   }else{
            return false;
		   }
           }
		   catch(Exception $e)
		   {
             print_r($e->getMessage());
             redirect('error');
		   }
	}

	public function getRecentPostsByUser($email,$howMany){
		$em = $this->_em;

		$publish = "PUBLISHED";
		$limit = $howMany;

		$dql = "SELECT p FROM Entity\Post p JOIN p.author a WHERE a.email = :email AND (p.postStatus = :publish AND p.isAnonymous = :f) ORDER BY p.updatedOn DESC ";
		$q = $em->createQuery($dql)
			->setParameter('email',$email)
			->setParameter('publish',$publish)
			->setParameter('f',false)
			->setMaxResults($limit);

		$posts = $q->getResult();

		if(null != $posts){
			return $posts;
		}else{
			return false;
		}
	}

	public function getRelatedPostsByUser($email, $howMany, $categId) {
		$em = $this->_em;

		$publish = "PUBLISHED";
		$limit = $howMany;
		
		$categ = $em->getRepository('Entity\Category')->findOneBy(array('id' => $categId));

		$dql = "SELECT p FROM Entity\Post p JOIN p.author a WHERE a.email = :email AND (p.postStatus = :publish AND p.isAnonymous = 0) AND p.category = :categ ORDER BY p.updatedOn DESC ";
		$q = $em->createQuery($dql)
			->setParameter('email',$email)
			->setParameter('publish',$publish)
			->setParameter('categ',$categ)
			->setMaxResults($limit);

		$posts = $q->getResult();

		if(null != $posts){
			return $posts;
		}else{
			return false;
		}
	}


	public function getRatedPostsForUser($user){
		$em = $this->_em;

		$ratedPosts = $em->getRepository('Entity\RatingLog')->findBy(array('user'=>$user),array('id'=>'desc'));

		if(null != $ratedPosts){
			return $ratedPosts;
		}else{
			return false;
		}
	}

	public function getSubscriptionsForUser($user){
		$em = $this->_em;

		$subscriptions = $em->getRepository('Entity\Follow')->findBy(array('user'=>$user),array('id'=>'desc'));

		if(null != $subscriptions){
			return $subscriptions;
		}else{
			return false;
		}
	}

	public function getLatestPostForUser($userId){
		$em = $this->_em;
		$publish = "PUBLISHED";

		$dql = "SELECT p FROM Entity\Post p JOIN p.author a WHERE a.id = :userId AND p.postStatus = :publish ORDER BY p.updatedOn DESC";
		$q = $em->createQuery($dql)
				->setParameter('userId',$userId)
				->setParameter('publish',$publish)
				->setMaxResults(1);

		$latestPost = $q->getSingleResult();

		$data = array();
		if(null != $latestPost){
			return $latestPost;
			/*$data['id'] = $latestPost->getId();
			$files = $latestPost->getFiles();
			foreach ($files as $file) {
				$data['file'] = $file->getFilepath();
				break;
			}
			$data['title'] = $latestPost->getHeadline();
			$data['postType'] = $latestPost->getPostType();
			$data['hashtags'] = array();
			foreach ($latestPost->getHashtags() as $hashtag) {
				$hash = array();
				$hash['id'] = $hashtag->getId();
				$hash['name'] = $hashtag->getHashtag();
				$data['hashtags'][] = $hash;
			}
			$data['date'] = $latestPost->getUpdatedOn()->format('d-m-Y h:m');
			$data['slug'] = $latestPost->getSlug();*/

			//return $data;
		}else{
			return false;
		}
	}

	public function getSearchResults($searchQuery){
        try{
        	$em = $this->_em;
			$qb = $em->createQueryBuilder();

        	$q = $qb->select(array('p','a'))
					->from('Entity\Post', 'p')
					->leftJoin('p.author','a')
					->where('p.headline LIKE :word')
			       	->orWhere('p.description LIKE :word')
				  	->orWhere('a.userName LIKE :word')
				  	->andWhere('p.postStatus LIKE :publish')
				  	->andWhere('p.isAnonymous = :anonymous')
		        	->setParameter('word', '%'.$searchQuery.'%')
		        	->setParameter('publish',"PUBLISHED")
		        	->setParameter('anonymous',false)
		        	->orderBy('p.postRanking','DESC')
		        	->getQuery();

	          $post = $q->getResult();

			  if(null != ($post))
			  {
	              return $post;
			  }
			  else {
	            return FALSE;
			}
	    }
	    catch(Exception $e) {
	        print_r($e->getMessage());
	        // redirect('error');
		}
	}

	public function getSearchResultsByHashtags($searchQuery,$hashtag){
        try{
        	$em = $this->_em;

        	$dql = "SELECT p,h FROM Entity\Post p LEFT JOIN p.hashTags h WHERE :hashtag MEMBER OF p.hashTags AND p.postStatus = :publish";

        	$q = $em->createQuery($dql);
        	$q->setParameter('hashtag',$hashtag);
        	$q->setParameter('publish',"PUBLISHED");

        	$data = $q->getResult();

        	if(!is_null($data)){
        		return $data;
        	}else{
        		return false;
        	}
	    }
	    catch(Exception $e) {
	        print_r($e->getMessage());
	        die();
		}
	}


	public function getPostBySlug($slug) {
		$em = $this->_em;
		$post = $this->findOneBy(array('slug' => $slug));
		if(!is_null($post)) {
			return $post;
		}
		return false;
	}

	public function getPostsSharedByPeopleUserFollows($user){
		$em = $this->_em;
	}

	function getHashtagsForPostsTodayWithMaxShares($date){
		$em = $this->_em;

		$dateToday = $date;

		$limit = 20;

		$dql = "SELECT p FROM Entity\Post p WHERE (YEAR(p.updatedOn) = :year AND MONTH(p.updatedOn) = :month AND DAY(p.updatedOn) = :day) ORDER BY p.sharedCount DESC";
		$q = $em->createQuery($dql)
			->setParameter('year', intval($dateToday->format('Y')))
			->setParameter('month', intval($dateToday->format('m')))
			->setParameter('day', intval($dateToday->format('d')))
			->setMaxResults($limit);

		$posts = $q->getResult();

		if(null != $posts){
			$data = array();

			foreach ($posts as $post) {
				foreach ($post->getHashtags() as $hashtag) {
					$data[] = $hashtag->getHashtag();
				}
			}

			return $data;
		}else{
			return false;
		}
	}

	public function getPostsByCategory($category){
		$em = $this->_em;

		$posts = $this->findBy(array('category'=>$category,'postStatus'=>array('PUBLISHED')),array('postRanking'=>'desc'));

		if(null != $posts){
			return $posts;
		}else{
			return false;
		}
	}

	public function getPostsForUser($user){
		try {

			$em = $this->_em;

			$posts = $this->findBy(array('author'=>$user),array('postRanking'=>'desc') , 5);

			if(null != $posts){
				return $posts;
			}else{
				return false;
			}
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}


	public function getPostsForUserHashtags($user){
		try {

			$em = $this->_em;

			$posts = $this->findBy(array('author'=>$user),array('id'=>'desc') , 5);

			if(null != $posts){
				return $posts;
			}else{
				return false;
			}
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
		}
	}


	public function getFeaturedPosts()
	{
		$em = $this->_em;

		$posts = $this->findBy(array('postStatus'=>array('PUBLISHED'),'isFeatured'=>1),array('postRanking'=>'desc'));

		if(null != $posts){
			return $posts;
		}else{
			return false;
		}						
	}

	public function getTrendingNewsPosts()
	{
		$em = $this->_em;

		$posts = $this->findBy(array('postStatus'=>array('PUBLISHED')),array('postRanking'=>'desc'));

		if(null != $posts){
			return $posts;
		}else{
			return false;
		}
	}

	public function getLocationBasedPosts($location){
		try {

			$em = $this->_em;

			$qb = $em->createQueryBuilder();

			$q = $qb->select(array('p'))
					->from('Entity\Post', 'p')
				  	->where('p.location LIKE :location')
				  	->andWhere('p.postStatus = :publish')
					->setParameter('location','%'.$location.'%')
					->setParameter('publish','PUBLISHED')
					->setMaxResults(5)
					->getQuery();

			$posts = $q->getResult();

			if($posts != null){
				return $posts;
			}else{
				return false;
			}
		} catch (Exception $e) {
			
		}
	}
}
