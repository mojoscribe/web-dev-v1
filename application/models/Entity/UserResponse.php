<?php
namespace Entity;
/**
* @Entity
* @Table(name="userResponse")
*/
class UserResponse{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="User", inversedBy="userResponse")
	 * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	protected $user;

	/**
	 * @ManyToOne(targetEntity="Poll", inversedBy="userResponse")
	 * @JoinColumn(name="poll_id", referencedColumnName="id", nullable=false)
	 */
	protected $poll;

	/**
	 * @ManyToOne(targetEntity="PollOptions", inversedBy="userResponse")
	 * @JoinColumn(name="optionText_id", referencedColumnName="id", nullable=false)
	 */
	protected $optionText;

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

	public function getPoll()
	{
	    return $this->poll;
	}

	public function setPoll($poll)
	{
	    $this->poll = $poll;
	    return $this;
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
}