<?php


namespace Radio\Mapper\Validator;


class Min
{

    private $size = 0;

    function __construct($size)
    {
        $this->size = $size;
    }

    function validate($val)
    {
        if ($val != null && strlen($val) < $this->size) {
            return "A mező hossza legalább $this->size kell hogy legyen";
        }
        return true;
    }


} 