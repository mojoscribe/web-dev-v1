<?php

namespace Entity;
use Repository;
/**
 * @Entity(repositoryClass="Repository\RatingLogRepository")
 * @Table(name="rating_log")
 */
class RatingLog {
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
    
    /**
     * @Column(type="float",  nullable=false)
     */
    protected $rating;

    /**
    * @ManyToOne(targetEntity="Impact")
    */
    protected $userImpact;
	 
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
	public function getRating() {
		return $this->rating;
	}
	public function setRating($rating) {
		$this->rating = $rating;
		return $this;
	}

	public function getUserImpact()
	{
	    return $this->userImpact;
	}
	
	public function setUserImpact($userImpact)
	{
	    $this->userImpact = $userImpact;
	    return $this;
	}
}