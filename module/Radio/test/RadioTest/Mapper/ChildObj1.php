<?php

namespace RadioTest\Mapper;

class ChildObj1 {
   public $prop1;
   public $prop2;

    /**
     * @param mixed $prop1
     */
    public function setProp1($prop1) {
        $this->prop1 = $prop1;
    }

    /**
     * @return mixed
     */
    public function getProp1() {
        return $this->prop1;
    }

    /**
     * @param mixed $prop2
     */
    public function setProp2($prop2) {
        $this->prop2 = $prop2;
    }

    /**
     * @return mixed
     */
    public function getProp2() {
        return $this->prop2;
    }




}
