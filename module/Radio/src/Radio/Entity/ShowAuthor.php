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
     * @ORM\Column(type="string") 
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
        return $this->nick;
    }

}

?>