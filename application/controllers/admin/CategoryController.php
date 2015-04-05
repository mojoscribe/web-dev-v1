<?php 
use Entity\Category;
class CategoryController extends CI_Controller {
 	function __construct(){
 		parent::__construct();
 	}
 
 	function index(){
 		$this->checkSession ();
 		try {
 			$this->load->view ( 'admin/header', array (
					'userName' => $_SESSION ['adminUserName'] , 'home' => 1
			) );
			$categories = $this->doctrine->em->getRepository('Entity\Category')->findBy(array(), array('order'=>'asc'));
			$this->load->view('admin/category/list',array('categories' => $categories));

			$this->load->view ( 'admin/footer', array (
					'adminHome' => false 
			) );
 		} catch (Exception $e) {
 			
 		}
 		
 	}

 	function add(){

 		$this->checkSession();	
 		try {
 			$this->load->view ( 'admin/header', array (
					'userName' => $_SESSION ['adminUserName'] , 'home' => 1
			) );
			 
			$this->load->view('admin/category/add');

			$this->load->view ( 'admin/footer', array (
					'adminHome' => false 
			) );
 		} catch (Exception $e) {
 			
 		}
 	}

 	function save(){
 		$this->checkSession();
 		try {
 			$category = new Category();
 			$category->setName($_POST['name']);
 			$category->setOrder($_POST['order']);
 			$this->doctrine->em->persist($category);
 			$this->doctrine->em->flush();
 			redirect('admin/categories?add=success');
 		} catch (Exception $e) { 		
 			redirect('admin/categories?add=fail&msg='.$e->getMessage());
 		}
 	}

 	function edit(){
 		$this->checkSession();
 		try {
 			if(isset($_GET['id'])) {
 				$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id' => $_GET['id']));
 				if(!is_null($category)) {	
 					$this->load->view ( 'admin/header', array (
							'userName' => $_SESSION ['adminUserName'] , 'home' => 1
					) );
					 
					$this->load->view('admin/category/edit', array('category' => $category));

					$this->load->view ( 'admin/footer', array (
							'adminHome' => false 
					) );	
 				} else {
 					throw new Exception("Invalid Category ID Given");
 				}
 			} else {
 				throw new Exception("Category id not given");
 			}
 		} catch (Exception $e) { 		
 			redirect('admin/categories?add=fail&msg='.$e->getMessage());
 		}	
 	}

 	function update(){
 		$this->checkSession();
 		try {
 			$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id' => $_POST['id']));

 			if(!is_null($category)) { 				
 				$category->setName($_POST['name']);
	 			$category->setOrder($_POST['order']);
	 			$this->doctrine->em->persist($category);
	 			$this->doctrine->em->flush();
	 			redirect('admin/categories?add=success');	
 			} else {
 				throw new Exception("invalid category id", 1);
 			}
 			
 		} catch (Exception $e) { 		
 			redirect('admin/categories?add=fail&msg='.$e->getMessage());
 		}	
 	}

 	function delete(){ 		
 		echo $_GET['id'];
 		try {
 			$category = $this->doctrine->em->getRepository('Entity\Category')->findOneBy(array('id' => $_GET['id']));
 			
 			if(!is_null($category)) {
 				$this->doctrine->em->remove($category); 				
 				$this->doctrine->em->flush();
 				redirect('admin/categories');
 			} else {
 				redirect('admin/categories');
 			}
 		} catch (Exception $e) {
 			redirect('admin/categories');
 		}
 	}

 	function checkSession() {
		try {
			session_start ();
			if (isset ( $_SESSION ['adminUserId'] )) {
				return $_SESSION ['adminUserId'];
			} else {
				redirect ( 'admin' );
			}
		} catch ( Exception $e ) {
			redirect ( 'error' );
		}
	}

 }