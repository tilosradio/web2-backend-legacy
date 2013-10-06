<?php

namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="author")
 * */
class Author {

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     * */
    protected $id;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="ShowAuthor",mappedBy="author", fetch="EAGER")
     */
    protected $shows;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function getShows() {
        return $this->shows;
    }

        public function toArray() {
        $a = $this->toArrayShort();
        return $a;
    }

    public function toArrayShort() {
        $a = array();
        $a['id'] = $this->getId();
        $a['name'] = $this->getName();
        return $a;
    }

}

?>