<?php
namespace Entity;
/**
* @MappedSuperclass
* @HasLifecycleCallbacks
*
*/
class BaseEntity{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @Column(type="datetime", nullable=false)
	 */
	protected $createdOn;

	/**
	* @Column(type="datetime", length=255, nullable=false)
	*/
	protected $updatedOn;

	public function __construct(){
		$this->createdOn = new \DateTime();
	}

	/** 
 	*  @PrePersist 
 	*/
	public function doStuffOnPrePersist()
	{
	    $this->updatedOn = new \DateTime();
	}

	public function getUpdatedOn()
	{
	    return $this->updatedOn;
	}
	
	public function getCreatedOn()
	{
	    return $this->createdOn;
	}
}