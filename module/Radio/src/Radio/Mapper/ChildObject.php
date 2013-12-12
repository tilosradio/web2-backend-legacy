<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/12/13
 * Time: 7:58 PM
 */

namespace Radio\Mapper;


class ChildObject implements Mapper {

    private $mappers = [];
    private $name;

    function __construct($name) {
        $this->name = $name;
    }

    public function map(&$from, &$to) {
        if (array_key_exists($this->name, $from)) {
            if (!array_key_exists($this->name, $to)) {
                $to[$this->name] = [];
            }
            foreach ($this->mappers as $mapper) {
                $mapper->map($from[$this->name], $to[$this->name]);
            }
        }
    }

    public function addMapper($mapper) {
        $this->mappers[] = $mapper;
        return $mapper;
    }
} 