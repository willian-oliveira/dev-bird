<?php

namespace SONUser\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SONUser\Entity\User;

/**
 * Esta classe insere dados para teste
 */
class LoadUser extends AbstractFixture {

    /**
     * Insere no banco de dados um usuario para teste
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setName('Willian Ricardo Oliveira')
                ->setEmail('willian@outlook.com')
                ->setPassword(123456)
                ->setActive(true);
        $manager->persist($user);
        $manager->flush();
    }

}
