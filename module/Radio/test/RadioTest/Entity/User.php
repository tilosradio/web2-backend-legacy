<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/13/13
 * Time: 8:47 PM
 */

namespace RadioTest\Entity;


class User extends \PHPUnit_Framework_TestCase {

    public function testUser() {

        $u = new \Radio\Entity\User();
        $u->createSalt();
        $u->setPassword("testpassword");
        echo "\n";
        echo $u->getSalt()."\n";
        echo $u->getPassword();
    }

} 