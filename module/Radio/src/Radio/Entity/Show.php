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
     * @ORM\Column(type="string", length=100) 
     * */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Contribution",mappedBy="show", fetch="EAGER")
     */
    protected $contributors;

    /**
     * @ORM\OneToMany(targetEntity="Scheduling", mappedBy="show")
     */
    protected $schedulings;

    /**
     * @ORM\Column(type="string", length=255, nullable=true) 
     * */
    protected $definition;

    /**
     * @ORM\Column(type="string", length=25,nullable=true) 
     * */
    protected $alias;

    /**
     * @ORM\Column(type="string", length=50,nullable=true) 
     * */
    protected $banner;

    /**
     * @ORM\Column(type="text",nullable=true) 
     * */
    protected $description;

    /**
     * @ORM\Column(type="integer") 
     * */
    protected $type;
    
     /**
     * @ORM\ManyToMany(targetEntity="Url")
     * @ORM\JoinTable(name="show_url",
     *      joinColumns={@ORM\JoinColumn(name="show_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="url_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $urls = array();
    
    public function getUrls() {
        return $this->urls;
    }

    public function setUrls($urls) {
        $this->urls = $urls;
    }
    public function addUrl($url) {
        $this->urls[] = $url;
    }
    
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

    public function getSchedulings() {
        return $this->schedulings;
    }

    public function getAlias() {
        return $this->alias;
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
        $a['alias'] = $this->getAlias();
        $a['banner'] = $this->getBanner();
        $a['definition'] = $this->getDefinition();
        $a['type'] = $this->getType();
        return $a;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function getContributors() {
        return $this->contributors;
    }

    public function setContributors($contributors) {
        $this->contributors = $contributors;
    }

     
    public function setSchedulings($schedulings) {
        $this->schedulings = $schedulings;
    }

    public function setDefinition($definition) {
        $this->definition = $definition;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

    public function setBanner($banner) {
        $this->banner = $banner;
    }

    public function setDescription($description) {
        $this->description = $description;
    }


}

?>