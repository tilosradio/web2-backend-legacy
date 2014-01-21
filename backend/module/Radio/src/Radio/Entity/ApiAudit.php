<?php


namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="api_audit")
 **/
class ApiAudit {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $user;
    /**
     * @ORM\Column(type="string",length=40)
     */
    private $url;
    /**
     * @ORM\Column(type="string",length=500)
     */
    private $postParams;
    /**
     * @ORM\Column(type="datetime")
     */
    private $callDate;
    /**
     * @ORM\Column(type="string",length=10)
     */
    private $method;

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $postParams
     */
    public function setPostParams($postParams) {
        $this->postParams = $postParams;
    }

    /**
     * @return mixed
     */
    public function getPostParams() {
        return $this->postParams;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param mixed $callDate
     */
    public function setCallDate($callDate) {
        $this->callDate = $callDate;
    }

    /**
     * @return mixed
     */
    public function getCallDate() {
        return $this->callDate;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getMethod() {
        return $this->method;
    }


}