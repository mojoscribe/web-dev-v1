<?php
namespace Entity;
/**
* @Entity
* @Table(name="notifications")
*/
class Notifications{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @Column(type="string", nullable=false)
	 */
	protected $type;

	public function getId()
	{
	    return $this->id;
	}

	public function getType()
	{
	    return $this->type;
	}
	
	public function setType($type)
	{
	    $this->type = $type;
	    return $this;
	}

}