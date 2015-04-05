<?php 
class PagesController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/RSSGen.php');
	}

	function index(){
		$this->load->view('header');

		$this->load->view('footer');
	}

	function about(){
		
		$profile = array();
		if($user = isUserLoggedIn()) {
			$profile['userName'] = $user->getUserName();
			$profile['picture'] = $user->getProfilePicturePath();
			
		}
		$this->load->view('header',array('data'=>$profile));
		// $this -> load -> view('user/header');
		$this->load->view('navigation');
		$this->load->view('pages/about');
		$this->load->view('footer');	
	}

	function contact(){
		
		$profile = array();
		if($user = isUserLoggedIn()) {
			$profile['userName'] = $user->getUserName();
			$profile['picture'] = $user->getProfilePicturePath();
			
			
		}
		$this->load->view('header',array('data'=>$profile));
		// $this->load->view('user/header');
		$this->load->view('navigation');
		$this->load->view('pages/contact');
		$this->load->view('footer',array('scripts'=>array('controllers/contactController.js')));	
	}

	function rss(){
		try {
			$recentPosts = $this->doctrine->em->getRepository('Entity\Post')->findBy(array('postStatus'=>array('PUBLISHED')),array('updatedOn'=>'desc'),10);


			if(!is_null($recentPosts)){
				header('Content-Type:application/xml');
				$xml = '<rss version="2.0">';

				$xml .= '<channel>';

				$xml .= '<title>MojoScribe</title>';
				$xml .= '<link>'.base_url('rss/feed').'</link>';
				$xml .= '<description>MojoScribe</description>';

				foreach ($recentPosts as $post) {
					$xml .= '<item>'."\n";
					$xml .= '<title>'.$post->getHeadline().'</title>'."\n";
					$xml .= '<link>'.base_url()."single/".$post->getSlug().'</link>'."\n";
					$xml .= '<pubDate>'.$post->getUpdatedOn()->format('d-M-Y').'</pubDate>';

					$files = $post->getFiles();

					$temp = "";
					foreach ($files as $file) {
						$temp = $file->getThumb();
						break;
					}

					$xml .= '<image>'.base_url().$temp.'</image>';

					$xml .= '</item>';

				}

				$xml .= '</channel>';

				$xml .= '</rss>';

				// $xml = xml_encode($xml);

				print_r($xml);

				// $xml = xmlrpc_decode_request($xml, null);

				// print_r($xml);


				// $this->load->view('pages/rss');
			}else{

			}
		} catch (Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			die();
			// redirect('technicalProblem');
		}
	}
}
