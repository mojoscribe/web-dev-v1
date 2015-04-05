<?php
namespace Entity;
/**
* @Entity
* @Table(name="flagLog")
*/
class FlagLog{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	* @ManyToOne(targetEntity="User")
	*/
	protected $user;

	/**
	* @ManyToOne(targetEntity="Post",cascade={"remove"})
	*/
	protected $post;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $reason;

	public function getId()
	{
	    return $this->id;
	}
	
	public function getUser()
	{
	    return $this->user;
	}
	
	public function setUser($user)
	{
	    $this->user = $user;
	    return $this;
	}

	public function getPost()
	{
	    return $this->post;
	}
	
	public function setPost($post)
	{
	    $this->post = $post;
	    return $this;
	}

	public function getReason()
	{
	    return $this->reason;
	}
	
	public function setReason($reason)
	{
	    $this->reason = $reason;
	    return $this;
	}
}