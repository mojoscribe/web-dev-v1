<?php

namespace Entity;
use Repository;
use Entity\BaseEntity;

/**
 * @Entity(repositoryClass="Repository\HashtagsRepository")
 * @Table(name="hashtags")
 */
class Hashtags extends BaseEntity{
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
    protected $hashtag;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $hashtagUseCount;

    public function getId()
    {
        return $this->id;
    }

    public function getHashtag()
    {
        return $this->hashtag;
    }
    
    public function setHashtag($hashtag)
    {
        $this->hashtag = $hashtag;
        return $this;
    }

    public function getHashtagUseCount()
    {
        return $this->hashtagUseCount;
    }
    
    public function setHashtagUseCount($hashtagUseCount)
    {
        $this->hashtagUseCount = $hashtagUseCount;
        return $this;
    }
    
}