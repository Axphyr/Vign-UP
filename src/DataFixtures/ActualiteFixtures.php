<?php

namespace App\DataFixtures;

use App\Factory\ActualiteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActualiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ActualiteFactory::createMany(35);

        $manager->flush();
    }
}
