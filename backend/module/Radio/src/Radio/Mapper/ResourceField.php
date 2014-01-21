<?php

namespace Radio\Mapper;

/**
 * Resource field mapping based on name.
 *
 * @package Radio\Mapper
 */
class ResourceField extends FieldConverter
{

    private $baseUrl;

    function __construct($name, $baseUrl)
    {
        parent::__construct($name);
        $this->baseUrl = $baseUrl;
    }


    protected function  convert($from)
    {
        $var = $this->baseUrl . "/upload/" . $from;
        return $var;
    }
}