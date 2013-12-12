<?php


namespace Radio\Mapper;


class ChildCollection implements Mapper {

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
            foreach ($from[$this->name] as &$child) {
                $n = [];
                foreach ($this->mappers as $mapper) {
                    $mapper->map($child, $n);
                }
                $to[$this->name][] = $n;
            }
        }
    }

    public function addMapper($mapper) {
        $this->mappers[] = $mapper;
        return $mapper;
    }
} 