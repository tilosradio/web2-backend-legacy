<?php

namespace Radio\Mapper;

/**
 * Resource field mapping based on name.
 *
 * @package Radio\Mapper
 */
class ResourceField implements Mapper {

    private $name;
    private $baseUrl;

    function __construct($name, $baseUrl) {
        $this->name = $name;
        $this->baseUrl = $baseUrl;
    }

    public function map(&$from, &$to) {
        if (array_key_exists($this->name, $from)) {
            $to[$this->name] = $this->baseUrl . "/upload/" . $from[$this->name];
        }
    }
}