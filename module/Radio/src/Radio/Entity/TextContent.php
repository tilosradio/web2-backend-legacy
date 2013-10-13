<?php

namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="textcontent")
 * */
class TextContent {

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     * */
    protected $id;

    /**
     * @ORM\Column(type="string",length=255) 
     * */
    protected $title;

    /**
     * @ORM\Column(type="string",length=15) 
     * */
    protected $type;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $content;

    /**
     * @ORM\Column(type="datetime") 
     * */
    protected $created;

    /**
     * @ORM\Column(type="string") 
     * */
    protected $author;

}

?>