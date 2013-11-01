<?php

namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="showauthor")
 * */
class ShowAuthor {

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     * */
    protected $id;

    /**
     * @ORM\Column(type="string",length=100) 
     * */
    protected $nick;

    /**
     * @ORM\ManyToOne(targetEntity="Show", inversedBy="authors")
     * @ORM\JoinColumn(name="radioshow_id", referencedColumnName="id")
     * */
    protected $show;

    /**
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="shows")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * */
    protected $author;

    public function getShow() {
        return $this->show;
    }

    public function getId() {
        return $this->id;
    }

    public function getNick() {
        return empty($this->nick) ? $this->getAuthor()->getName() : $this->nick;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNick($nick) {
        $this->nick = $nick;
    }

    public function setShow($show) {
        $this->show = $show;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

}

?>