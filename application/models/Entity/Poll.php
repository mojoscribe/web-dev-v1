<?php

namespace Entity;
use Repository;
use Entity\BaseEntity;

/**
 * @Entity(repositoryClass="Repository\PollRepository")
 * @Table(name="poll")
 */
class Poll extends BaseEntity{

    /**
    * @ManyToOne(targetEntity="User")
    */
    protected $author;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $pollTitle;

    /**
     * @Column(type="text", nullable=true)
     */
    protected $pollContent;

    /**
     * @Column(type="string", nullable=false)
     */
    protected $slug;

    /**
	 * @OneToMany(targetEntity="PollOptions", mappedBy="poll",cascade={"remove"})
	 */
	protected $optionText;

	/**
	 * @OneToMany(targetEntity="UserResponse", mappedBy="poll")
	 */
	protected $userresponse;

	 public function __construct() {
        parent::__construct();
        $this->optionText = new \Doctrine\Common\Collections\ArrayCollection();
		$this->userresponse = new \Doctrine\Common\Collections\ArrayCollection();
        }

	public function getId() {
		return $this->id;
	}
	public function getAuthor() {
		return $this->author;
	}
	public function setAuthor($author) {
		$this->author = $author;
		return $this;
	}
	public function getPollTitle() {
		return $this->pollTitle;
	}
	public function setPollTitle($pollTitle) {
		$this->pollTitle = $pollTitle;
		return $this;
	}
	public function getPollContent() {
		return $this->pollContent;
	}
	public function setPollContent($pollContent) {
		$this->pollContent = $pollContent;
		return $this;
	}

	public function addOptionText(\Entity\PollOptions $optionText)
	{
		$this->optionText[] = $optionText;
		return $this;
	}

	public function getOptionText()
	{
		return $this->optionText;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}

    public function getUserResponse() {
		return $this->userResponse;
	}

	public function addUserResponse(\Entity\UserResponse $userResponse) {
		$this->userResponse[] = $userResponse;
		return $this;
	}
}