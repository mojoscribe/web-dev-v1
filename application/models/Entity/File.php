<?php
namespace Entity;
use Entity\BaseEntity;

/**
* @Entity
* @Table(name="file")
*/
class File extends BaseEntity{
	
	/**
	 * @Column(type="string", nullable=false)
	 */
	protected $filepath;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $type;

	/**
	* @ManytoOne(targetEntity="Post", inversedBy="id",cascade={"persist"})
	*/
	protected $post;

	/**
	* @Column(type="string", length=255, name="small_image", nullable=true)
	*/
	protected $small;

	/**
	* @Column(type="string", length=255, name="thumb_image", nullable=true)
	*/
	protected $thumb;

	/**
	* @Column(type="string", length=255, name="long_image", nullable=true)
	*/
	protected $long;

	/**
	* @Column(type="string", length=255, name="big_image", nullable=true)
	*/
	protected $big;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $newsroom;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $mp4;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $ogg;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $webm;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $deviceImage;


	public function getId()
	{
	    return $this->id;
	}
	

	public function getFilepath()
	{
	    return $this->filepath;
	}
	
	public function setFilepath($filepath)
	{
	    $this->filepath = $filepath;
	    return $this;
	}

	public function getType() {
	    return $this->type;
	}
	
	public function setType($type) {
	    $this->type = $type;
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

	public function getSmall() {
	    return $this->small;
	}
	
	public function setSmall($small) {
	    $this->small = $small;
	    return $this;
	}

	public function getThumb() {
	    return $this->thumb;
	}
	
	public function setThumb($thumb) {
	    $this->thumb = $thumb;
	    return $this;
	}

	public function getLong() {
	    return $this->long;
	}
	
	public function setLong($long) {
	    $this->long = $long;
	    return $this;
	}

	public function getBig() {
	    return $this->big;
	}
	
	public function setBig($big) {
	    $this->big = $big;
	    return $this;
	}

	public function getNewsroom() {
	    return $this->newsroom;
	}
	
	public function setNewsroom($newsroom) {
	    $this->newsroom = $newsroom;
	    return $this;
	}

	public function getMp4() {
	    return $this->mp4;
	}
	
	public function setMp4($mp4) {
	    $this->mp4 = $mp4;
	    return $this;
	}

	public function getOgg() {
	    return $this->ogg;
	}
	
	public function setOgg($ogg) {
	    $this->ogg = $ogg;
	    return $this;
	}

	public function getWebm() {
	    return $this->webm;
	}
	
	public function setWebm($webm) {
	    $this->webm = $webm;
	    return $this;
	}

	public function getDeviceImage()
	{
	    return $this->deviceImage;
	}
	
	public function setDeviceImage($deviceImage)
	{
	    $this->deviceImage = $deviceImage;
	    return $this;
	}
}