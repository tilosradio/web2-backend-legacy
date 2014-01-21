<?php


namespace Radio\Util;


class AccessControlUtil
{

    public static function showOwner($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');
        // identify the user
        $user = $authService->hasIdentity() ? $authService->getIdentity() : null;
        if ($user == null) {
            return false;
        }
        $role = $user->getRole();
        if ($role->getName() == "admin") {
            return true;
        }

        $id = $e->getRouteMatch()->getParam("id");
        if ($role->getName() == "author") {
            $em = $serviceManager->get('doctrine.entitymanager.orm_default');
            $qb = $em->createQueryBuilder();
            $qb->select('s', 'c', 'a', 'u')->from('\Radio\Entity\Show', 's');
            $qb->leftJoin('s.contributors', 'c');
            $qb->leftJoin('c.author', 'a');
            $qb->leftJoin('a.user', 'u');
            $qb->where("u.id = :uid AND s.id = :id");


            $q = $qb->getQuery();
            $q->setParameter("uid", $user->getId());
            $q->setParameter("id", $id);
            return count($q->getArrayResult()) > 0;
        }
        return false;


    }

    public static function authorOwner($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');
        // identify the user
        $user = $authService->hasIdentity() ? $authService->getIdentity() : null;
        if ($user == null) {
            return false;
        }
        $role = $user->getRole();
        if ($role->getName() == "admin") {
            return true;
        }

        $id = $e->getRouteMatch()->getParam("id");
        if ($role->getName() == "author") {
            $em = $serviceManager->get('doctrine.entitymanager.orm_default');
            $qb = $em->createQueryBuilder();
            $qb->select('a', 'u')->from('\Radio\Entity\Author', 'a');
            $qb->leftJoin('a.user', 'u');
            $qb->where("u.id = :uid AND a.id = :id");


            $q = $qb->getQuery();
            $q->setParameter("uid", $user->getId());
            $q->setParameter("id", $id);
            return count($q->getArrayResult()) > 0;
        }
        return false;


    }

} 