<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\MessageFactory;
use App\Factory\SujetFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; ++$i) {
            MessageFactory::createOne(['sujet' => SujetFactory::random(), 'user' => UserFactory::random()]);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SujetFixtures::class,
        ];
    }
}
