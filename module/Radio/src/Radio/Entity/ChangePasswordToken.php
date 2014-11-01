<?php
namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM,
    Radio\Permissions\Acl,
    Radio\Entity\Role;

/**
 * @ORM\Entity
 * @ORM\Table(name="change_password")
 * */
class ChangePasswordToken {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="user")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\Column(type="string",length=40)
     */
    private $token;
    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user) {
        $this->user = $user;
    }


}