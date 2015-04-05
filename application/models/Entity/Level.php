<?php

namespace Entity;
use Repository;
/**
 * @Entity(repositoryClass="Repository\LevelRepository")
 * @Table(name="level")
 */
class Level {
   /**
	* @Id
    * @Column(type="integer", nullable=false)
    * @GeneratedValue(strategy="AUTO")
    */
    protected $id;
	/**
     * @Column(type="string", length = 255, nullable=false)
     */
    protected $name;
	 
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
}