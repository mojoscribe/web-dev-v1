<?php 
use Entity\TrendingHashtags;
class HashtagsCronController extends CI_Controller {
	var $postRepository;
	var $hashtagsRepository;
	function __construct(){
		parent::__construct();
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->hashtagsRepository = $this->doctrine->em->getRepository('Entity\Hashtags');
		$this->load->helper('date');
	}

	function index(){
		$date = new DateTime();

		/*Get hashtags for the top 20 most shared posts for today till the given time */
		$hashtagsForTodaysPosts = $this->postRepository->getHashtagsForPostsTodayWithMaxShares($date);

		$requiredDate = new DateTime();

		$pastDate = getPastDate($requiredDate,15);

		/*Get most used hashtags in past 15 days */
		$topHashtagsInPastDays = $this->hashtagsRepository->getTopHashtagsInPastDays($pastDate,$date);

		/* */
		$trendingHashtags = array_intersect($hashtagsForTodaysPosts, $topHashtagsInPastDays);

		// echo "<pre>";
		// print_r($trendingHashtags);
		// die();

		$n = 1;
		foreach ($trendingHashtags as $trendingHashtag) {

			$existing = $this->doctrine->em->getRepository('Entity\TrendingHashtags')->findOneBy(array('name'=>$trendingHashtag));

			if($existing){
				$existing->setHashtagRank($n);
			}else{
				$trending = new TrendingHashtags();

				$trending->setName($trendingHashtag);
				$trending->setHashtagRank($n);

				$this->doctrine->em->persist($trending);
				$this->doctrine->em->flush();
			}

			$n++;
		}

		$time = time();
		// file_put_contents("cronOP/".$time, "done");
		echo "<pre>";
		print_r("done");
		die();
	}
}
?>