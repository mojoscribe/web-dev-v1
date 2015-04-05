<?php
namespace Entity;
use Repository;
use Entity\BaseEntity;
/**
 * @Entity
 * @Table(name="admin")
 */
 class Admin extends BaseEntity
 {
 	
	/**
	 * @Column(type="string", length=255, nullable=false)
	 */
	protected $username; 
	 /**
	  * @Column(type="string", length=255, nullable=false)
	  */
	protected $password;
	public function getId() {
		return $this->id;
	}
	public function getUsername() {
		return $this->username;
	}
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}
	public function getPassword() {
		return $this->password;
	}
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	
	
	
 }
