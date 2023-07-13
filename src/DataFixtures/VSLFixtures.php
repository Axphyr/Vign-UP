<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Factory\VSLFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VSLFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        VSLFactory::createOne(['latitude' => 34.889, 'longitude' => 32.481, 'superficie' => 2, 'description' => 'Stroumpi', 'approved' => 1]);
        for ($i = 0; $i < 8; ++$i) {
            VSLFactory::createOne();
        }
        $manager->flush();
    }
}
