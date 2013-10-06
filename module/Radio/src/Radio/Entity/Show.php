<?php

namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="radioshow")
 * */
class Show {

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
     * @ORM\OneToMany(targetEntity="ShowAuthor",mappedBy="show", fetch="EAGER")
     */
    protected $authors;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $definition;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $slug;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $banner;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $description;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAuthors() {
        return $this->authors;
    }

    public function getDefinition() {
        return $this->definition;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getBanner() {
        return $this->banner;
    }

    public function getDescription() {
        return $this->description;
    }

    public function toArray() {
        $a = $this->toArrayShort();
        $a['description'] = $this->getDescription();
        return $a;
    }

    public function toArrayShort() {
        $a = array();
        $a['id'] = $this->getId();
        $a['name'] = $this->getName();
        $a['slug'] = $this->getSlug();
        $a['banner'] = $this->getBanner();
        $a['definition'] = $this->getDefinition();
        return $a;
    }

}
?>