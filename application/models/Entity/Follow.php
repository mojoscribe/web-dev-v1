<?php

namespace Entity;
use Repository;

/**
 * @Entity(repositoryClass="Repository\FollowRepository")
 * @Table(name="follow")
 */
class Follow {
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
	* @ManyToOne(targetEntity="User")
	*/
	 protected $author;

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
	public function getAuthor() {
		return $this->author;
	}
	public function setAuthor($author) {
		$this->author = $author;
		return $this;
	}
}