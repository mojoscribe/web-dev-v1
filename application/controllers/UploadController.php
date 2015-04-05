<?php
use Entity\Post;
class UploadController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->file('application/classes/Response.php');
	}

	function index(){
		$this->load->view('header');
		$this->load->view('user/header');
		$this->load->view('user/upload');
		$this->load->view('user/footer');
		$this->load->view('footer',array('scripts' => array('upload.js')));
	}

	function upload(){

		$response = new Response();


		if($_SERVER['REQUEST_METHOD'] == "POST"){

			try {
				$date = new DateTime();
				//$post = new Post();

				//$post->setDate($date);

 				  $config ['upload_path'] = './uploads/';
				$config ['allowed_types'] = 'jpeg|png|jpg|avi|mp4|3gp|mpg';
				     $config ['max_size'] = '30000'; //30MB  /*What is the limit?*/

				$this->load->library ( 'upload', $config );

				if (!$this->upload->do_upload('file')) {
					redirect('upload?error='.$this->upload->display_errors());
				} else {
					$data = $this->upload->data();
					$post->setMediaFilePath(base_url('uploads/'.$data['file_name']));

					$this->doctrine->em->persist($post);

				}

				$path = $post->getMediaFilePath();

				$this->doctrine->em->flush();

				$response->setSuccess(true);
				$response->setData(array(
						'imgPath' => $path
					));
				$response->setError('');

				$response->respond();
				die();

			} catch (Exception $e) {
				$response->setSuccess(false);
				$response->setData('');
				$response->setError(array('msg'=>"Something Went wrong!"));
			}
		}else{
			$response->setSuccess(false);
			$response->setData('');
			$response->setError(array('msg'=>"Something Went wrong!"));
		}
	}
}