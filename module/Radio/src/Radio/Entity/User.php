<?php
namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM,
    Radio\Permissions\Acl,
    Radio\Entity\Role;

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
    
    /**
     * @ORM\OneToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;
    
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
    
    public function createSalt()
    {
        // do not regenerate existing salt
        if (empty($this->salt))
            $this->salt = sha1(date('YmdHis') . str(mt_rand()));
        return $this->salt;
    }
    
    /**
     * @return Radio\Entity\Role
     */
    public function getRole()
    {
        return empty($this->role) ? Role::getDefault() : $this->role;
    }
    
    public static function testPassword(User $user, $passwordGiven)
    {
        return sha1($passwordGiven . $user->getSalt()) === $user->getPassword();
    }
    
    public function toArray()
    {
        return array(
            'id'        => $this->id,
            'username'  => $this->username,
            'email'     => $this->email,
            'password'  => $this->password,
            'salt'      => $this->salt,
        );
    }
    
    public function toArraySafe()
    {
        return array(
            'id'        => $this->id,
            'username'  => $this->username,
            'email'     => $this->email,
        );
    }
 }