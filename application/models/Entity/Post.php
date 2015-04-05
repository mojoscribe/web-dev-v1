<?php

namespace Entity;
use Repository;
use \Doctrine\Common\Collections\ArrayCollection;
use Entity\BaseEntity;

/**
 * @Entity(repositoryClass="Repository\PostRepository")
 * @Table(name="post")
 */
class Post extends BaseEntity{

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $headline;

    /**
    * @Column(type="text", nullable=true)
    */
    protected $description;

	/**
	* @OneToMany(targetEntity="File", mappedBy="post",cascade={"remove"})
	*/
	protected $files;

    /**
     * @ManyToOne(targetEntity="Impact")
     */
    protected $userImpact;

    /**
     * @ManyToOne(targetEntity="Category")
     */
    protected $category;

    /**
     * @ManyToMany(targetEntity="Hashtags")
     */
    protected $hashTags;

    /**
    * @ManyToOne(targetEntity="Level")
    */
    protected $level;

    /**
    * @ManyToOne(targetEntity="User")
    */
    protected $author;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
	protected $location;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $locality;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $city;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $state;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $country;

	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $slug;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $postStatus;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $postType;

	/**
	* @OneToOne(targetEntity="PostDetails", cascade={"remove"})
	*/
	protected $postDetails;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $sourceOfMedia;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $sharedCount;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $postRanking;

	/**
	* @Column(type="boolean", nullable=true)
	*/
	protected $isFeatured;

	/**
	* @Column(type="boolean", nullable=true)
	*/
	protected $isBreaking;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $isTrending;

	/**
	* @Column(type="boolean", nullable=true)
	*/
	protected $isAnonymous;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $latitude;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $longitude;


	public function __construct(){
		parent::__construct();
		$this->hashTags = new ArrayCollection();
		$this->files = new ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}
	public function getHeadline() {
		return $this->headline;
	}
	public function setHeadline($headline) {
		$this->headline = $headline;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	public function addFiles(\Entity\File $files)
	{
		$this->files[] = $files;
		return $this;
	}
	public function getFiles()
	{
		return $this->files;
	}
	public function getUserImpact() {
		return $this->userImpact;
	}
	public function setUserImpact($userImpact) {
		$this->userImpact = $userImpact;
		return $this;
	}
	public function getCategory() {
		return $this->category;
	}
	public function setCategory($category) {
		$this->category = $category;
		return $this;
	}
	public function addHashTag($hashTag)
	{
		$this->hashTags[] = $hashTag;
		return $this;
	}
	public function getHashTags()
	{
		return $this->hashTags;
	}
	public function getLevel() {
		return $this->level;
	}
	public function setLevel($level) {
		$this->level = $level;
		return $this;
	}
	public function getAuthor() {
		return $this->author;
	}
	public function setAuthor($author) {
		$this->author = $author;
		return $this;
	}
	public function getLocation() {
		return $this->location;
	}
	public function setLocation($location) {
		$this->location = $location;
		return $this;
	}
	public function getSlug() {
		return $this->slug;
	}
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}

	public function getPostStatus()
	{
	    return $this->postStatus;
	}

	public function setPostStatus($postStatus)
	{
	    $this->postStatus = $postStatus;
	    return $this;
	}

	public function getPostType()
	{
	    return $this->postType;
	}

	public function setPostType($postType)
	{
	    $this->postType = $postType;
	    return $this;
	}

	public function getPostDetails()
	{
	    return $this->postDetails;
	}
	
	public function setPostDetails($postDetails)
	{
	    $this->postDetails = $postDetails;
	    return $this;
	}

	public function getSourceOfMedia()
	{
	    return $this->sourceOfMedia;
	}
	
	public function setSourceOfMedia($sourceOfMedia)
	{
	    $this->sourceOfMedia = $sourceOfMedia;
	}

	public function getSharedCount()
	{
	    return $this->sharedCount;
	}
	
	public function setSharedCount($sharedCount)
	{
	    $this->sharedCount = $sharedCount;
	    return $this;
	}


	public function getPostRanking()
	{
	    return $this->postRanking;
	}
	
	public function setPostRanking($postRanking)
	{
	    $this->postRanking = $postRanking;
	    return $this;
	}

	public function getIsFeatured()
	{
	    return $this->isFeatured;
	}
	
	public function setIsFeatured($isFeatured)
	{
	    $this->isFeatured = $isFeatured;
	    return $this;
	}

	public function getIsBreaking()
	{
	    return $this->isBreaking;
	}
	
	public function setIsBreaking($isBreaking)
	{
	    $this->isBreaking = $isBreaking;
	    return $this;
	}

	public function getIsTrending()
	{
	    return $this->isTrending;
	}
	
	public function setIsTrending($isTrending)
	{
	    $this->isTrending = $isTrending;
	    return $this;
	}

	public function getIsAnonymous()
	{
	    return $this->isAnonymous;
	}
	
	public function setIsAnonymous($isAnonymous)
	{
	    $this->isAnonymous = $isAnonymous;
	    return $this;
	}

	public function getLatitude(){
	    return $this->latitude;
	}
	
	public function setLatitude($latitude){
	    $this->latitude = $latitude;
	    return $this;
	}

	public function getLongitude(){
	    return $this->longitude;
	}
	
	public function setLongitude($longitude){
	    $this->longitude = $longitude;
	    return $this;
	}

	public function getLocality(){
	    return $this->locality;
	}
	
	public function setLocality($locality){
	    $this->locality = $locality;
	    return $this;
	}

	public function getCity(){
	    return $this->city;
	}
	
	public function setCity($city){
	    $this->city = $city;
	    return $this;
	}

	public function getState(){
	    return $this->state;
	}
	
	public function setState($state){
	    $this->state = $state;
	    return $this;
	}

	public function getCountry(){
	    return $this->country;
	}
	
	public function setCountry($country){
	    $this->country = $country;
	    return $this;
	}
	
}