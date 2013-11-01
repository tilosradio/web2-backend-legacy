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
     * @ORM\Column(type="string", length=20, nullable=true) 
     * */
    protected $alias;
    
    /**
     * @ORM\Column(type="string",length=20) 
     * */
    protected $photo;
    
    /**
     * @ORM\Column(type="string",length=20) 
     * */
    protected $avatar;
    
    /**
     * @ORM\Column(type="text") 
     * */
    protected $introduction;

    /**
     * @ORM\OneToMany(targetEntity="ShowAuthor",mappedBy="author", fetch="EAGER")
     */
    protected $showAuthors;
    
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\OneToMany(targetEntity="AuthorUrl", mappedBy="author")
     **/
    protected $urls;

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

    public function getPhoto() {
        return $this->photo;
    }
    
    public function setPhoto($photo) {
        $this->photo = $photo;
    }
    
    public function getAvatar() {
        return $this->avatar;
    }
    
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }
    
    public function getIntroduction() {
        return $this->introduction;
    }
    
    public function setIntroduction($introduction) {
        $this->introduction = $introduction;
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function setUser($user) {
        $this->user = $user;
    } 
    
    public function getShowAuthors() {
        return $this->showAuthors;
    }
    
    public function getAlias() {
        return $this->alias;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

    
    public function toArray() {
        $a = $this->toArrayShort();
        $a['introduction'] = $this->getIntroduction();
        $a['photo'] = $this->getPhoto();
        return $a;
    }

    public function toArrayShort() {
        $a = array();
        $a['id'] = $this->getId();
        $a['name'] = $this->getName();
        $a['avatar'] = $this->getAvatar();
        $a['alias'] = $this->getAlias();
        return $a;
    }
    
    public function getUrls() {
        return $this->urls;
    }



}

?>