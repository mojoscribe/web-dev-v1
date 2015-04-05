<?php
namespace Entity;
/**
* @Entity
* @Table(name="userVerification")
*/
class UserVerification{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	* @OneToOne(targetEntity="User")
	*/
	protected $user;
	
	/**
	 * @Column(type="string", nullable=false)
	 */
	protected $verificationLink;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $type;

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

	public function getVerificationLink()
	{
	    return $this->verificationLink;
	}
	
	public function setVerificationLink($verificationLink)
	{
	    $this->verificationLink = $verificationLink;
	    return $this;
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