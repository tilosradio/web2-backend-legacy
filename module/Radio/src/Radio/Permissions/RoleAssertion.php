<?php

namespace Radio\Permissions;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\Permissions\Acl\Assertion\AssertionInterface,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\RoleInterface,
    Zend\Permissions\Acl\Resource\ResourceInterface,
    Radio\Provider\ServiceLocator,
    Radio\Provider\EntityManager;

class RoleAssertion implements AssertionInterface, ServiceLocatorAwareInterface {
    use ServiceLocator;
    use EntityManager;
    
    private $recordId;
    
    /**
     * Id of the record in the database (if any)
     * 
     * @param int $id 
     */
    public function __construct($recordId=0)
    {
        $this->recordId = $recordId;
    }
    
    /**
     * Database record level permission control
     * 
     * @param \Zend\Permissions\Acl\Acl $acl
     * @param \Zend\Permissions\Acl\Role\RoleInterface $role
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     * @param type $privilege
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $action = null) {
        // check if the author belongs to this show
        if ($role->getRoleId() == 'author' && in_array($action, array('create', 'delete')))
        {
            /*
             * if (this show doesn't belong to this author) 
             *     return false;
             */
        }
        /*
        var_dump($role->getRoleId());
        var_dump($resource->getResourceId());
        */
        return true;
    }
}