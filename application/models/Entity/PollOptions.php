<?php
namespace Entity;
use Entity\BaseEntity;

/**
* @Entity
* @Table(name="pollOptions")
*/
class PollOptions extends BaseEntity{

	/**
	 * @Column(type="string", nullable=false)
	 */
	protected $optionText;

	/**
	 * @ManyToOne(targetEntity="Poll", inversedBy="id",cascade={"persist"})
	 */
	protected $poll;

	/**
	 * @OneToMany(targetEntity="UserResponse", mappedBy="optionText")
	 */
	protected $userResponse;

	public function getId() {
	    return $this->id;
	}

	public function getOptionText()
	{
	    return $this->optionText;
	}

	public function setOptionText($optionText)
	{
	    $this->optionText = $optionText;
	    return $this;
	}

	public function getPoll()
	{
	    return $this->poll;
	}

	public function setPoll($poll)
	{
	    $this->poll = $poll;
	    return $this;
	}

	public function getUserResponse(){
		return $this->userResponse;
	}

	public function addUserResponse(\Entity\UserResponse $userResponse){
		$this->userResponse[] = $userResponse;
		return $this;
	}
}