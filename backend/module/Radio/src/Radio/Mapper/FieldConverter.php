<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 1/5/14
 * Time: 11:14 AM
 */

namespace Radio\Mapper;


abstract class FieldConverter implements Mapper
{

    private $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    public function map(&$from, &$to, $setter)
    {
        if (array_key_exists($this->name, $from)) {
            $var = $this->convert($from[$this->name]);
            $setter->set($to, $this->name, $var);
        }
    }

    abstract protected function convert($from);


} 