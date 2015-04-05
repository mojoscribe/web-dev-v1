<?php 
namespace Entity;
/**
 * @Entity(repositoryClass="Repository\CountryRepository")
 * @Table(name="country")
 */
class Country {
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
	protected $currencyCode;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $fipsCode;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $countryCode;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $isoNumeric;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $north;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $capital;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $continentName;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $areaInSqKm;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $languages;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $isoAlpha3;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $continent;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $south;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $east;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $west;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $geonameId;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $population;


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

	public function getCurrencyCode() {
		return $this->currencyCode;
	}
	
	public function setCurrencyCode($currencyCode) {
		$this->currencyCode = $currencyCode;
		return $this;
	}

	public function getFipsCode() {
		return $this->fipsCode;
	}
	
	public function setFipsCode($fipsCode) {
		$this->fipsCode = $fipsCode;
		return $this;
	}

	public function getCountryCode() {
		return $this->countryCode;
	}
	
	public function setCountryCode($countryCode) {
		$this->countryCode = $countryCode;
		return $this;
	}

	public function getIsoNumeric() {
		return $this->isoNumeric;
	}
	
	public function setIsoNumeric($isoNumeric) {
		$this->isoNumeric = $isoNumeric;
		return $this;
	}

	public function getNorth() {
	    return $this->north;
	}
	
	public function setNorth($north) {
	    $this->north = $north;
	    return $this;
	}

	public function getCapital() {
		return $this->capital;
	}
	
	public function setCapital($capital) {
		$this->capital = $capital;
		return $this;
	}

	public function getContinentName() {
		return $this->continentName;
	}
	
	public function setContinentName($continentName) {
		$this->continentName = $continentName;
		return $this;
	}

	public function getAreaInSqKm() {
		return $this->areaInSqKm;
	}
	
	public function setAreaInSqKm($areaInSqKm) {
		$this->areaInSqKm = $areaInSqKm;
		return $this;
	}

	public function getLanguages() {
		return $this->languages;
	}
	
	public function setLanguages($languages) {
		$this->languages = $languages;
		return $this;
	}

	public function getIsoAlpha3() {
		return $this->isoAlpha3;
	}
	
	public function setIsoAlpha3($isoAlpha3) {
		$this->isoAlpha3 = $isoAlpha3;
		return $this;
	}

	public function getContinent() {
		return $this->continent;
	}
	
	public function setContinent($continent) {
		$this->continent = $continent;
		return $this;
	}

	public function getSouth() {
		return $this->south;
	}
	
	public function setSouth($south) {
		$this->south = $south;
		return $this;
	}

	public function getEast() {
		return $this->east;
	}
	
	public function setEast($east) {
		$this->east = $east;
		return $this;
	}

	public function getWest() {
		return $this->west;
	}
	
	public function setWest($west) {
		$this->west = $west;
		return $this;
	}

	public function getGeonameId() {
		return $this->geonameId;
	}
	
	public function setGeonameId($geonameId) {
		$this->geonameId = $geonameId;
		return $this;
	}

	public function getPopulation() {
		return $this->population;
	}
	
	public function setPopulation($population) {
		$this->population = $population;
		return $this;
	}

}