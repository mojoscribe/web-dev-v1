<?php

namespace Entity;

/**
 * @Entity(repositoryClass="Repository\CityRepository")
 * @Table(name="city")
 */
class City {
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @Column(type="string", length=32, nullable=true)
	 */
	protected $name;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $geoNamesCountryId;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $fclName;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $lng;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $fCodeName;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $toponymName;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $fcl;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $fcode;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $geonameId;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $lat;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	protected $population;
	
	/**
	 * @ManyToOne(targetEntity="Country")
	 * @JoinColumn(name="country_id", referencedColumnName="id")
	 */
	protected $country;
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getGeoNamesCountryId() {
		return $this->geoNamesCountryId;
	}
	public function setGeoNamesCountryId($geoNamesCountryId) {
		$this->geoNamesCountryId = $geoNamesCountryId;
		return $this;
	}
	public function getFclName() {
		return $this->fclName;
	}
	public function setFclName($fclName) {
		$this->fclName = $fclName;
		return $this;
	}
	public function getLng() {
		return $this->lng;
	}
	public function setLng($lng) {
		$this->lng = $lng;
		return $this;
	}
	public function getFCodeName() {
		return $this->fCodeName;
	}
	public function setFCodeName($fCodeName) {
		$this->fCodeName = $fCodeName;
		return $this;
	}
	public function getToponymName() {
		return $this->toponymName;
	}
	public function setToponymName($toponymName) {
		$this->toponymName = $toponymName;
		return $this;
	}
	public function getFcl() {
		return $this->fcl;
	}
	public function setFcl($fcl) {
		$this->fcl = $fcl;
		return $this;
	}
	public function getFcode() {
		return $this->fcode;
	}
	public function setFcode($fcode) {
		$this->fcode = $fcode;
		return $this;
	}
	public function getGeonameId() {
		return $this->geonameId;
	}
	public function setGeonameId($geonameId) {
		$this->geonameId = $geonameId;
		return $this;
	}
	public function getLat() {
		return $this->lat;
	}
	public function setLat($lat) {
		$this->lat = $lat;
		return $this;
	}
	public function getPopulation() {
		return $this->population;
	}
	public function setPopulation($population) {
		$this->population = $population;
		return $this;
	}
	public function getCountry() {
		return $this->country;
	}
	public function setCountry($country) {
		$this->country = $country;
		return $this;
	}
	
}