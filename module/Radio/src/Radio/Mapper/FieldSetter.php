<?php
namespace Radio\Mapper;


interface FieldSetter
{

    public function set(&$container, $propertyName, &$value);

    public function ensureExists(&$container, $propertyName, $type);

    public function add(&$container, $propertyName, &$value);

    public function createEmptyChild($type);

}

?>