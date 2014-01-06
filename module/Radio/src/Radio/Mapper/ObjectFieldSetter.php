<?php
namespace Radio\Mapper;

class ObjectFieldSetter implements FieldSetter
{

    function set(&$container, $propertyName, &$value)
    {
        $setter = "set" . strtoupper(substr($propertyName, 0, 1)) . substr($propertyName, 1);
        $container->$setter($value);
    }

    function ensureExists(&$container, $propertyName, $type)
    {
        $getter = "get" . $propertyName;
        if (empty($container->$getter())) {
            $t = new $type();
            $this->set($container, $propertyName, $t);
        }
        return $container->$getter();

    }

    public function add(&$container, $propertyName, &$value)
    {
        $setter = "add" . $propertyName;
        $container->$setter($value);
    }

    public function createEmptyChild($type)
    {
        return new $type();
    }
}

?>