<?php

namespace Radio\Permissions;

use Zend\Permissions\Acl\Acl as ZendAcl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource,
    Radio\Permissions\RoleAssertion,
    Radio\Permissions\PermissionException as Exception;

class Acl extends ZendAcl
{
    const DEFAULT_ROLE = 'guest';

    public function __construct($config, $role, $controller, $action)
    {
        // validate config
        if (!isset($config['acl']['roles']))
            throw new Exception('Invalid ACL config found');

        // load roles
        $roles = $config['acl']['roles'];
        if (!isset($roles[self::DEFAULT_ROLE]))
            $roles[self::DEFAULT_ROLE] = null;
        foreach ($roles as $name => $parent)
            if (!$this->hasRole($name)) {
                $parent = empty($parent) ? array() : explode(',', $parent);
                $this->addRole(new Role($name), $parent);
            }

        if (!$this->hasResource($controller))
            $this->addResource(new Resource($controller));
        $this->allow($role, $controller, $action);

    }
}