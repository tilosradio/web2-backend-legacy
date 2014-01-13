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
     * @ORM\Column(type="string",length=20)
     */
    private $username;

    /**
     * @ORM\Column(type="string",length=40, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string",length=40, nullable=true)
     */
    private $salt;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

    /**
     * @ORM\OneToOne(targetEntity="Author",mappedBy="user", fetch="EAGER")
     */
    protected $author;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }


    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = sha1($password . $this->getSalt());
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSalt()
    {
        if (empty($this->salt)) {
            $this->createSalt();
        }
        return $this->salt;
    }

    public function createSalt()
    {
        // do not regenerate existing salt
        if (empty($this->salt))
            $this->salt = sha1(date('YmdHis') . mt_rand());
        return $this->salt;
    }

    /**
     * @return Radio\Entity\Role
     */
    public function getRole()
    {
        return empty($this->role) ? Role::getDefault() : $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public static function testPassword(User $user, $passwordGiven)
    {
        return sha1($passwordGiven . $user->getSalt()) === $user->getPassword();
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'salt' => $this->salt,
        );
    }

    public function toArraySafe()
    {
        return array(
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
        );
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }


}