<?php
namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="user")
 * */
class User
{
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     * */
    private $id;
    
    /**
     * @ORM\Column(length=20)
     */
    private $username;
    
    /**
     * @ORM\Column(length=40)
     */
    private $password;
    
    /**
     * @ORM\Column(length=255)
     */
    private $email;
    
    /**
     * @ORM\Column(length=40)
     */
    private $salt;
}