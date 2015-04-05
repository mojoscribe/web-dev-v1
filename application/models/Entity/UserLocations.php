<?php
namespace Entity;
/**
* @Entity
* @Table(name="user_locations")
*/
class UserLocations{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @Column(type="string", nullable=false)
	 */
	protected $locationName;

	/**
	* @ManytoOne(targetEntity="User")
	*/
	protected $user;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $latitude;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $longitude;

	public function getId()
	{
	    return $this->id;
	}
	
	public function getLocationName()
	{
	    return $this->locationName;
	}
	
	public function setLocationName($locationName)
	{
	    $this->locationName = $locationName;
	    return $this;
	}

	public function getUser()
	{
	    return $this->user;
	}
	
	public function setUser($user)
	{
	    $this->user = $user;
	    return $this;
	}

	public function getLatitude()
	{
	    return $this->latitude;
	}
	
	public function setLatitude($latitude)
	{
	    $this->latitude = $latitude;
	    return $this;
	}

	public function getLongitude()
	{
	    return $this->longitude;
	}
	
	public function setLongitude($longitude)
	{
	    $this->longitude = $longitude;
	    return $this;
	}
}