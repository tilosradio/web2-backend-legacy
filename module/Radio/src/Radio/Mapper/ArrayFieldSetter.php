<?php
namespace Radio\Mapper;

class ArrayFieldSetter implements FieldSetter
{
    function set(&$container, $propertyName, &$value)
    {
        $container[$propertyName] = $value;
    }

    public function ensureExists(&$container, $propertyName, $type)
    {
        if (!array_key_exists($propertyName, $container)) {
            $container[$propertyName] = array();
        }
        return $container[$propertyName];
    }

    public function add(&$container, $propertyName, &$value)
    {
        $container[$propertyName][] = $value;
    }

    public function createEmptyChild($type)
    {
        return [];
    }
}

?>