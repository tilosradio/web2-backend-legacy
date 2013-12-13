<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/12/13
 * Time: 7:58 PM
 */

namespace Radio\Mapper;


class ListMapper implements Mapper {

    private $mappers = [];

    function __construct() {

    }

    public function map(&$from, &$to) {
        foreach ($from as $item) {
            $newValue = [];
            foreach ($this->mappers as $mapper) {
                $mapper->map($item, $newValue);
            }
            $to[] = $newValue;
        }
        return $to;
    }

    public function addMapper($mapper) {
        $this->mappers[] = $mapper;
        return $mapper;
    }
} 