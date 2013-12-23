<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/23/13
 * Time: 4:21 PM
 */

namespace RadioTest\Fixitures;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Radio\Entity\User;

class BaseData implements FixtureInterface {

    public function load(ObjectManager $manager) {




        $user = new \Radio\Entity\User();
        $user->setId(1);
        $user->setUsername("test");
        $user->setSalt("salto");
        $user->setPassword("NOHASHX");
        $user->setEmail("test@test.hu");

        $metadata = $manager->getClassMetaData(get_class($user));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());

        $manager->persist($user);
        $manager->flush();
    }

}