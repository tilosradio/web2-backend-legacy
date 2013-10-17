<?php
namespace Radio\Entity;

use Doctrine\ORM\Mapping as ORM,
    Radio\Permissions\Acl;

/**
 * @ORM\Entity 
 * @ORM\Table(name="role")
 * */
class Role
{
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     * */
    protected $id;

    /**
     * @ORM\Column(length=10)
     */
    protected $name;
    
    /**
     * @ORM\OneToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")
     */
    protected $parent;
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return Radio\Entity\Role
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * @return \Radio\Entity\Role
     */
    public static function getDefault()
    {
        static $def = null;
        if (null === $def)
        {
            $def = new Role();
            $def->id = 1;
            $def->name = Acl::DEFAULT_ROLE;
            $def->parent = null;
        }
        return $def;
    }
}