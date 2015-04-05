<?php

namespace Entity;
use Repository;
use Entity\BaseEntity;
/**
 * @Entity(repositoryClass="Repository\CategoryRepository")
 * @Table(name="category")
 */
class Category extends BaseEntity{

    /**
    * @Column(type="string", length=255, nullable=false)
    */
    protected $name;

    /**
    * @Column(type="integer",name="order_no")
    */
    protected $order;
    
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

	public function getOrder() {
	    return $this->order;
	}
	
	public function setOrder($order) {
	    $this->order = $order;
	    return $this;
	}
}