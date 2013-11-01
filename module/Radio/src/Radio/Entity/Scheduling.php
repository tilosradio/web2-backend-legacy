<?php

namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="scheduling")
 * */
class Scheduling {

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     * */
    protected $id;

    /**
     * @ORM\Column(type="smallint") 
     * */
    protected $weekType;

    /**
     * @ORM\Column(type="smallint") 
     * */
    protected $weekDay;

    /**
     * @ORM\Column(type="smallint") 
     * */
    protected $hourFrom;

    /**
     * @ORM\Column(type="smallint") 
     * */
    protected $minFrom;

    /**
     * @ORM\Column(type="smallint") 
     * */
    protected $duration;   
    /**
     * @ORM\Column(type="date") 
     * */
    protected $validFrom;

    /**
     * @ORM\Column(type="date") 
     * */
    protected $validTo;

    /**
     * @ORM\ManyToOne(targetEntity = "Show", inversedBy = "schedulings")
     * @ORM\JoinColumn(name = "radioshow_id", referencedColumnName = "id")
     */
    protected $show;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getWeekType() {
        return $this->weekType;
    }

    public function setWeekType($weekType) {
        $this->weekType = $weekType;
    }

    public function getWeekDay() {
        return $this->weekDay;
    }

    public function setWeekDay($weekDay) {
        $this->weekDay = $weekDay;
    }

    public function getHourFrom() {
        return $this->hourFrom;
    }

    public function setHourFrom($hourFrom) {
        $this->hourFrom = $hourFrom;
    }

    public function getMinFrom() {
        return $this->minFrom;
    }

    public function setMinFrom($minFrom) {
        $this->minFrom = $minFrom;
    }
  
    public function getValidFrom() {
        return $this->validFrom;
    }

    public function setValidFrom($validFrom) {
        $this->validFrom = $validFrom;
    }

    public function getValidTo() {
        return $this->validTo;
    }

    public function setValidTo($validTo) {
        $this->validTo = $validTo;
    }

    public function getShow() {
        return $this->show;
    }
    public function getDuration() {
        return $this->duration;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
    }

    public function toArray() {
        $a = array();
        $a['weekType'] = $this->getWeekType();
        $a['weekDay'] = $this->getWeekDay();
        $a['hourFrom'] = $this->getHourFrom();
        $a['minFrom'] = $this->getMinFrom();
        $a['duration'] = $this->getDuration();    
        return $a;
    }
    
    public function setShow($show) {
        $this->show = $show;
    }



}