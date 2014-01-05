<?php


namespace Radio\Mapper;


class ObjectMapper {

    private $mappers = [];
    private $setter;

    function __construct($setter = null) {
        if ($setter == null || empty($setter)) {
            $this->setter = new ArrayFieldSetter();
        } else {
            $this->setter = $setter;
        }

    }

    public function map(&$from, &$to) {
        foreach ($this->mappers as $mapper) {
            $mapper->map($from, $to, $this->setter);
        }
    }

    public function addMapper($mapper) {
        $this->mappers[] = $mapper;
        return $mapper;
    }
} 