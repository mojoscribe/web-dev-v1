<?php

namespace Entity;
use Repository;
/**
 * @Entity()
 * @Table(name="post_details")
 */
class PostDetails {
	/**
	* @Id
    * @Column(type="integer", nullable=false)
    * @GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @Column(type="string", nullable=true)
    */
    protected $popularity;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $numberOfViews;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    protected $rating;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    protected $numberOfShares;

    /**
	 * @Column(type="string", length=64, nullable=true)
	 */
    protected $numberOfRatingUsers;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $positive_sum;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $negative_sum;

	public function getId() {
		return $this->id;
	}
	public function getPopularity() {
		return $this->popularity;
	}
	public function setPopularity($popularity) {
		$this->popularity = $popularity;
		return $this;
	}
	public function getNumberOfViews() {
		return $this->numberOfViews;
	}
	public function setNumberOfViews($numberOfViews) {
		$this->numberOfViews = $numberOfViews;
		return $this;
	}
	public function getRating() {
		return $this->rating;
	}
	public function setRating($rating) {
		$this->rating = $rating;
		return $this;
	}
	public function getNumberOfShares() {
		return $this->numberOfShares;
	}
	public function setNumberOfShares($numberOfShares) {
		$this->numberOfShares = $numberOfShares;
		return $this;
	}

	public function getNumberOfRatingUsers() {
		return $this->numberOfRatingUsers;
	}
	public function setNumberOfRatingUsers($numberOfRatingUsers) {
		$this->numberOfRatingUsers = $numberOfRatingUsers;
		return $this;
	}
	public function getPost() {
		return $this->post;
	}
	public function setPost($post) {
		$this->post = $post;
		return $this;
	}

	public function getPositive_sum()
	{
	    return $this->positive_sum;
	}
	
	public function setPositive_sum($positive_sum)
	{
	    $this->positive_sum = $positive_sum;
	    return $this;
	}

	public function getNegative_sum()
	{
	    return $this->negative_sum;
	}
	
	public function setNegative_sum($negative_sum)
	{
	    $this->negative_sum = $negative_sum;
	    return $this;
	}
}