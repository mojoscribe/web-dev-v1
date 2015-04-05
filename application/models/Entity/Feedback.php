<?php

namespace Entity;
use Repository;
/**
 * @Entity(repositoryClass="Repository\FeedbackRepository")
 * @Table(name="feedback")
 */
class Feedback {
	/**
	* @Id
    * @Column(type="integer", nullable=false)
    * @GeneratedValue(strategy="AUTO")
    */
    protected $id;
	/**
	 * @Column(type="string", length=256, nullable=false)
	 */
    protected $user;
    /**
    * @Column(type="text", nullable=false)
    */
    protected $content;
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
	public function getContent() {
		return $this->content;
	}
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}


}