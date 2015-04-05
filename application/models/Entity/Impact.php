<?php

namespace Entity;
use Repository;
/**
 * @Entity(repositoryClass="Repository\ImpactRepository")
 * @Table(name="impact")
 */
class Impact {
	/**
	* @Id
    * @Column(type="integer", nullable=false)
    * @GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @Column(type="string", length=255, nullable=false)
    */
    protected $area;
    
	public function getId() {
		return $this->id;
	}
	public function getArea() {
		return $this->area;
	}
	public function setArea($area) {
		$this->area = $area;
		return $this;
	}
     
}