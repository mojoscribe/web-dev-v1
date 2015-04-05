<?php

namespace Entity;
use Repository;
/**
 * @Entity
 * @Table(name="reporter_level")
 */
class Reporterlevel {
	/**
	* @Id
    * @Column(type="integer", nullable=false)
    * @GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @Column(type="string", length=255, nullable=false)
    */
    protected $name;
    
}