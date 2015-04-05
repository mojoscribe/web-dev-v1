<?php

namespace Entity;
use Repository;
use Entity\BaseEntity;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Repository\UserRepository")
 * @Table(name="user")
 */
class User extends BaseEntity{
	
    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $firstName;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $lastName;

    /**
    * @Column(type="string", unique=true, length=255, nullable=false)
    */
    protected $email;
	
	/**
    * @Column(type="string", length=255, nullable=false)
    */
    protected $password;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $userName;
	
    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $contactNumber; 
	
	/**
    * @Column(type="string", length=255,  nullable=false)
    */
    protected $credibilityPoints; 

    /**
     * @Column(type="boolean",  nullable=false)
     */
    protected $isBannedStatus;
    
    /**
     * @Column(type="string", nullable=true)
     */
    protected $timeZone;

    /**
     * @Column(type="string", nullable=true)
     */
    protected $topItems;
    
    /**
     * @ManyToOne(targetEntity="Reporterlevel")
     */
    protected $reporterLevel;
    
    /**
	 * @OneToMany(targetEntity="UserResponse", mappedBy="user")
	 */
	protected $userResponse;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $city;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $country;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $profilePicturePath;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $gender;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $authToken;

	/**
	* @ManytoMany(targetEntity="Category")
	*/
	private $user_categoryPreference;

	/**
	* @Column(type="boolean", nullable=true)
	*/
	protected $isVerified;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $about;

	/**
	* @ManytoMany(targetEntity="Notifications")
	*/
	protected $user_notifications;

	/**
	* @OneToMany(targetEntity="UserLocations", mappedBy="user",cascade={"remove"})
	*/
	protected $user_locations;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $gcmId;

	/**
	* @Column(type="boolean", nullable=true)
	*/
	protected $hasSeen;
	

	public function __construct(){
		parent::__construct();
		$this->user_categoryPreference = new ArrayCollection();
		$this->user_notifications = new ArrayCollection();
		$this->user_locations = new ArrayCollection();

	}

	public function getId() {
		return $this->id;
	}
	public function getFirstName()
	{
	    return $this->firstName;
	}
	
	public function setFirstName($firstName)
	{
	    $this->firstName = $firstName;
	    return $this;
	}

	public function getLastName()
	{
	    return $this->lastName;
	}
	
	public function setLastName($lastName)
	{
	    $this->lastName = $lastName;
	    return $this;
	}

	public function getEmail() {
		return $this->email;
	}
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	public function getPassword() {
		return $this->password;
	}
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	public function getContactNumber() {
		return $this->contactNumber;
	}
	public function setContactNumber($contactNumber) {
		$this->contactNumber = $contactNumber;
		return $this;
	}
	public function getCredibilityPoints() {
		return $this->credibilityPoints;
	}
	public function setCredibilityPoints($credibilityPoints) {
		$this->credibilityPoints = $credibilityPoints;
		return $this;
	}
	public function getIsBannedStatus() {
		return $this->isBannedStatus;
	}
	public function setIsBannedStatus($isBannedStatus) {
		$this->isBannedStatus = $isBannedStatus;
		return $this;
	}
	
	public function getTimeZone() {
		return $this->timeZone;
	}
	public function setTimeZone($timeZone) {
		$this->timeZone = $timeZone;
		return $this;
	}


	public function getTopItems() {
		return $this->topItems;
	}
	public function setTopItems($topItems) {
		$this->topItems = $topItems;
		return $this;
	}
	public function getReporterLevel() {
		return $this->reporterLevel;
	}
	public function setReporterLevel($reporterLevel) {
		$this->reporterLevel = $reporterLevel;
		return $this;
	}
	
	public function getUserResponse(){
		return $this->userResponse;
	}
	
	public function addUserResponse(\Entity\UserResponse $userResponse){
		$this->userResponse[] = $userResponse;
		return $this;
	}

	public function getUserName()
	{
	    return $this->userName;
	}
	
	public function setUserName($userName)
	{
	    $this->userName = $userName;
	    return $this;
	}

	public function getCity()
	{
	    return $this->city;
	}
	
	public function setCity($city)
	{
	    $this->city = $city;
	    return $this;
	}

	public function getCountry()
	{
	    return $this->country;
	}
	
	public function setCountry($country)
	{
	    $this->country = $country;
	    return $this;
	}

	public function getProfilePicturePath()
	{
	    return $this->profilePicturePath;
	}
	
	public function setProfilePicturePath($profilePicturePath)
	{
	    $this->profilePicturePath = $profilePicturePath;
	    return $this;
	}

	public function getGender()
	{
	    return $this->gender;
	}
	
	public function setGender($gender)
	{
	    $this->gender = $gender;
	    return $this;
	}

	public function getUser_categoryPreference()
	{
	    return $this->user_categoryPreference;
	}
	
	public function addUser_categoryPreference(\Entity\Category $user_categoryPreference)
	{
	    $this->user_categoryPreference[] = $user_categoryPreference;
	    return $this;
	}

	public function getIsVerified()
	{
	    return $this->isVerified;
	}
	
	public function setIsVerified($isVerified)
	{
	    $this->isVerified = $isVerified;
	    return $this;
	}

	public function getAbout()
	{
	    return $this->about;
	}
	
	public function setAbout($about)
	{
	    $this->about = $about;
	    return $this;
	}

	public function getUser_notifications()
	{
	    return $this->user_notifications;
	}
	
	public function addUser_notifications(\Entity\Notifications $user_notifications)
	{
	    $this->user_notifications[] = $user_notifications;
	    return $this;
	}

	public function getUser_locations()
	{
	    return $this->user_locations;
	}
	
	public function addUser_locations(\Entity\UserLocations $user_locations)
	{
	    $this->user_locations[] = $user_locations;
	    return $this;
	}

	public function getAuthToken()
	{
	    return $this->authToken;
	}
	
	public function setAuthToken($authToken)
	{
	    $this->authToken = $authToken;
	    return $this;
	}


	public function getGcmId() {
	    return $this->gcmId;
	}
	
	public function setGcmId($gcmId) {
	    $this->gcmId = $gcmId;
	
	    return $this;
	}

	public function getHasSeen()
	{
	    return $this->hasSeen;
	}
	
	public function setHasSeen($hasSeen)
	{
	    $this->hasSeen = $hasSeen;
	    return $this;
	}
}