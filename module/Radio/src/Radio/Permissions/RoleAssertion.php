<?php

namespace Radio\Permissions;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\Permissions\Acl\Assertion\AssertionInterface,
    Zend\Permissions\Acl\Acl as ZendAcl,
    Zend\Permissions\Acl\Role\RoleInterface,
    Zend\Permissions\Acl\Resource\ResourceInterface,
    Radio\Provider\ServiceLocator,
    Radio\Provider\EntityManager,
    Radio\Entity\User;

class RoleAssertion implements AssertionInterface, ServiceLocatorAwareInterface {
    use ServiceLocator;
    use EntityManager;
    
    private $user;
    private $recordId;
    
    /**
     * Construct role validation object
     * 
     * @param User $user The user whos rights are going to be tested
     * @param type $recordId Id of the database record against which the permission will be tested
     */
    public function __construct($user, $recordId)
    {
        $this->user = $user;
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
    public function assert(ZendAcl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $action = null) {
        if (empty($this->user))
            // unauthorized user, permission check not possible (role level permissions apply)
            return true;
        if ($role->getRoleId() == 'author')
        {
            if ($resource->getResourceId('Radio\Controller\Show'))
            {
                if ($action == 'update')
                {
                    $author = $this->getEntityManager()
                                   ->getRepository('Radio\Entity\Author')
                                   ->findOneBy(array('user' => $this->user));
                    if (empty($author))
                        // no such author, permission denied
                        return false;
                    $showAuthors = $author->getShowAuthors();
                    foreach ($showAuthors as $showAuthor)
                        if ($showAuthor->getShow()->getId() == $this->recordId)
                            // author want's to update her own show, yay!
                            return true;
                    // this author doesn't belong to this show, permission denied
                    return false;
                }
            }
        }
        // again, role level permissions apply
        return true;
    }
}
