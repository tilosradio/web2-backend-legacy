<?php

namespace Radio\Permissions;

use Zend\Permissions\Acl\Acl as ZendAcl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource,
    Radio\Permissions\RoleAssertion,
    Radio\Permissions\PermissionException as Exception;

class Acl extends ZendAcl {
    const DEFAULT_ROLE = 'guest';

    public function __construct($config, RoleAssertion $assertion) {
        // validate config
        if (!isset($config['acl']['roles']) || !isset($config['acl']['resources']))
            throw new Exception('Invalid ACL config found');

        // load roles
        $roles = $config['acl']['roles'];
        if (!isset($roles[self::DEFAULT_ROLE]))
            $roles[self::DEFAULT_ROLE] = '';
        foreach ($roles as $name => $parent)
            if (!$this->hasRole($name)) {
                $parent = empty($parent) ? array() : explode(',', $parent);
                $this->addRole(new Role($name), $parent);
            }

        // load resources
        foreach ($config['acl']['resources'] as $permission => $controllers) {
            foreach ($controllers as $controller => $actions) {
                if ($controller == ':all')
                    $controller = null;
                else if (!$this->hasResource($controller))
                    $this->addResource(new Resource($controller));
                foreach ($actions as $action => $role) {
                    if ($action == ':all')
                        $action = null;
                    if ($permission == 'allow')
                        $this->allow($role, $controller, $action, $assertion);
                    elseif ($permission == 'deny')
                        $this->deny($role, $controller, $action);
                    else
                        throw new Exception('Invalid permission: ' . $permission);
                }
            }
        }
    }
}