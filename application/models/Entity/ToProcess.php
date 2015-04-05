<?php 
namespace Entity;
/**
 * @Entity
 * @Table(name="toProcess")
 */
class ToProcess extends BaseEntity {

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $userName;

    /**
    * @Column(type="string", length=255, nullable=true)
    */
    protected $vidPath;

    /**
    * @OneToOne(targetEntity="File")
    */
    protected $file;

    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getUserName() {
        return $this->userName;
    }
    
    public function setUserName($userName) {
        $this->userName = $userName;
        return $this;
    }

    public function getVidPath() {
        return $this->vidPath;
    }
    
    public function setVidPath($vidPath) {
        $this->vidPath = $vidPath;
        return $this;
    }

    public function getFile() {
        return $this->file;
    }
    
    public function setFile($file) {
        $this->file = $file;
        return $this;
    }
}