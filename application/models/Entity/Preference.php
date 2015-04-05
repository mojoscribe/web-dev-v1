<?php

namespace Entity;
use Repository;
/**
 * @Entity
 * @Table(name="preference")
 */
class Preference {
	/**
	* @Id
    * @Column(type="integer", nullable=false)
    * @GeneratedValue(strategy="AUTO")
    */
    protected $id;
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
    protected $location;
    /**
    * @Column(type="text", nullable=true)
    */
    protected $subscribedTopics;
	
	/**
	 * @Column(type="text", nullable=true)
	 */
	protected $likes;
}