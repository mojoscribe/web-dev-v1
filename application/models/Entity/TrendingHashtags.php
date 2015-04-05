<?php
namespace Entity;
/**
* @Entity
* @Table(name="trendingHashtags")
*/
class TrendingHashtags extends BaseEntity{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @Column(type="string", nullable=false)
	 */
	protected $name;

	/**
	* @Column(type="string", length=255, nullable=true)
	*/
	protected $hashtagRank;

	public function getId()
	{
	    return $this->id;
	}

	public function getName()
	{
	    return $this->name;
	}
	
	public function setName($name)
	{
	    $this->name = $name;
	    return $this;
	}

	public function getHashtagRank()
	{
	    return $this->hashtagRank;
	}
	
	public function setHashtagRank($hashtagRank)
	{
	    $this->hashtagRank = $hashtagRank;
	    return $this;
	}

}