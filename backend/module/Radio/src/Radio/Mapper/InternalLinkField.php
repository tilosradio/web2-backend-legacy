<?php

namespace Radio\Mapper;

/**
 * Resource field mapping based on name.
 *
 * @package Radio\Mapper
 */
class InternalLinkField extends FieldConverter
{

    private $name;

    function __construct($name, $baseUrl)
    {
        parent::__construct($name);
        $this->baseUrl = $baseUrl;
    }


    protected function  convert($from)
    {
        return $this->baseUrl . "/" . $from;
    }
}