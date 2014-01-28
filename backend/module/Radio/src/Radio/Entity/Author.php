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
     * @ORM\Column(type="string", nullable=true)
     * */
    protected $email;
    
    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * */
    protected $alias;
    
    /**
     * @ORM\Column(type="string",length=50)
     * */
    protected $photo;
    
    /**
     * @ORM\Column(type="string",length=20) 
     * */
    protected $avatar;
    
    /**
     * @ORM\Column(type="text") 
     * */
    protected $introduction = "";

    /**
     * @ORM\OneToMany(targetEntity="Contribution",mappedBy="author", fetch="EAGER")
     */
    protected $contributions;
    
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\ManyToMany(targetEntity="Url")
     * @ORM\JoinTable(name="author_url",
     *      joinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="url_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $urls = array();

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
    
  
    public function getContributions() {
        return $this->contributions;
    }

    public function setContributions($contributions) {
        $this->contributions = $contributions;
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
    
    public function addUrl($url) {
        $this->urls[] = $url;
    }
    
    public function setUrls($urls) {
        $this->urls = $urls;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }


}

?>