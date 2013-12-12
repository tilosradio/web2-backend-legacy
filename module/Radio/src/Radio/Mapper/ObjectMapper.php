<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/12/13
 * Time: 7:58 PM
 */

namespace Radio\Mapper;


class ObjectMapper implements Mapper {

    private $mappers = [];

    function __construct() {

    }

    public function map(&$from, &$to) {
        foreach ($this->mappers as $mapper) {
            $mapper->map($from, $to);
        }
    }

    public function addMapper($mapper) {
        $this->mappers[] = $mapper;
        return $mapper;
    }
} 