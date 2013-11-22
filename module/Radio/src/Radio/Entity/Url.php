<?php

namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="authorurl")
 * */
class Url {

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     * */
    protected $id;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $url;
    
    /**
     * @ORM\Column(type="string") 
     * */
    protected $type; 
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }


}

?>