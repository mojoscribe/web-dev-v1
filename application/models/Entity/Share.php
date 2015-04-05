<?php

namespace Entity;
use Repository;
/**
 * @Entity(repositoryClass="Repository\ShareRepository")
 * @Table(name="share")
 */
class Share {
	/**
	* @Id
    * @Column(type="integer", nullable=false)
    * @GeneratedValue(strategy="AUTO")
    */
    protected $id;
    /**
	 * @ManyToOne(targetEntity="User")
	 */
	 protected $user;
	 /**
	 * @ManyToOne(targetEntity="Post")
	 */
	 protected $post;
	 
	public function getId() {
		return $this->id;
	}
	public function getUser() {
		return $this->user;
	}
	public function setUser($user) {
		$this->user = $user;
		return $this;
	}
	public function getPost() {
		return $this->post;
	}
	public function setPost($post) {
		$this->post = $post;
		return $this;
	}
}