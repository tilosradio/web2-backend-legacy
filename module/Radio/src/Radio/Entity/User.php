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
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getSalt()
    {
        return $this->salt;
    }
    
    public function testPassword(User $user, $passwordGiven)
    {
        return sha1($passwordGiven . $user->getSalt()) === $user->getPassword();
    }
    
    public function toArray()
    {
        return get_object_vars($this);
    }
 }