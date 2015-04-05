<?php
use Entity\Post;
use Entity\Category;
use Entity\Hashtags;
use Entity\Impact;
use Entity\File;
class PreviewController extends CI_Controller {

	 var $userRepository;
	 var $postRepository;
	 var $impactRepository;
	 var $categoryRepository;
	 var $hashtagRepository;
	function __construct(){
		parent::__construct();
		$this->userRepository = $this->doctrine->em->getRepository('Entity\User');
		$this->postRepository = $this->doctrine->em->getRepository('Entity\Post');
		$this->impactRepository = $this->doctrine->em->getRepository('Entity\Impact');
		$this->categoryRepository = $this->doctrine->em->getRepository('Entity\Category');
		$this->hashtagRepository = $this->doctrine->em->getRepository('Entity\Hashtags');
		$this->load->helper('file_helper');
		$this->load->file('application/classes/Response.php');
	}

	function index() {
		/*render the single post preview using the id as get parameter from url */
		try {
			if(false != isUserLoggedIn()){
				$user = isUserLoggedIn();
				if($_SERVER['REQUEST_METHOD'] == "GET"){
					$post = $this->postRepository->getPost($_GET['id']);

					if(false != $post && $post->getPostStatus() == "Draft"){
						$data = array();

						$data['id'] = $post->getId();
						$data['title'] = $post->getHeadline();
						if($post->getPostStatus() == "Anonymous"){
							$data['author'] = "Anonymous";
						}else{
							$data['author'] = $post->getAuthor()->getUserName();
						}

						$data['date'] = $post->getCreatedOn()->format('d-M-Y');

						$data['description'] = $post->getDescription();
						$data['file'] = array();

						foreach ($post->getFiles() as $file) {
							$data['file'][] = $file->getFilePath();
							break;
						}

						$data['hashtags'] = array();

						foreach ($post->getHashtags() as $hashtag) {
							$data['hashtags'][] = $hashtag->getHashtag();
						}

						$data['postType'] = $post->getPostType();
						$data['postStatus'] = $post->getPostStatus();

						$data['impact'] = $post->getUserImpact()->getArea();

						$profile = array();
						$profile['id'] = $user->getId();
						$profile['userName'] = $user->getUserName();
						$profile['picture'] = $user->getProfilePicturePath();

						$this->load->view('header',array('data'=>$profile));
						// $this->load->view('user/header');
						$this->load->view('user/singlePost',array('postData'=>$data));
						$this->load->view('footer');

					}else{
						redirect('error');
					}
				}else{
					redirect('/');
				}
			}else{
				redirect('/?sessionexpired');
			}
		} catch(Exception $e) {
			print_r($e -> getMessage());
			redirect("technicalProblem");
		}
	}

}
