<?php
namespace Entity;
/**
* @Entity
* @Table(name="userNotifications")
*/
class UserNotifications extends BaseEntity{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @Column(type="string", nullable=false)
	 */
	protected $notifyText;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $link;

	/**
	* @ManytoOne(targetEntity="User")
	*/
	protected $user;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $image;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $actionType;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $actionId;

	/**
	* @Column(type="boolean", nullable=true)
	*/
	protected $isRead;

	public function getId()
	{
	    return $this->id;
	}

	public function getNotifyText()
	{
	    return $this->notifyText;
	}
	
	public function setNotifyText($notifyText)
	{
	    $this->notifyText = $notifyText;
	    return $this;
	}

	public function getLink()
	{
	    return $this->link;
	}
	
	public function setLink($link)
	{
	    $this->link = $link;
	    return $this;
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

	public function getImage()
	{
	    return $this->image;
	}
	
	public function setImage($image)
	{
	    $this->image = $image;
	    return $this;
	}


	public function getActionType() {
	    return $this->actionType;
	}
	
	public function setActionType($actionType) {
	    $this->actionType = $actionType;
	
	    return $this;
	}


	public function getActionId() {
	    return $this->actionId;
	}
	
	public function setActionId($actionId) {
	    $this->actionId = $actionId;
	
	    return $this;
	}

	public function getIsRead() {
	    return $this->isRead;
	}
	
	public function setIsRead($isRead) {
	    $this->isRead = $isRead;
	
	    return $this;
	}
}