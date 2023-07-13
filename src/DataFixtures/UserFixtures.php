<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'root@example.com', 'roles' => ['ROLE_ADMIN']]);
        UserFactory::createOne(['email' => 'vigneron@example.com', 'roles' => ['ROLE_VIGNERON']]);
        UserFactory::createOne(['email' => 'prestataire@example.com', 'roles' => ['ROLE_PRESTATAIRE']]);
        UserFactory::createOne(['email' => 'fournisseur@example.com', 'roles' => ['ROLE_FOURNISSEUR']]);
        UserFactory::createMany(50);
        $manager->flush();
    }
}
