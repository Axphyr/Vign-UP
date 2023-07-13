<?php

namespace App\DataFixtures;

use App\Factory\CategorieQuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function Zenstruck\Foundry\faker;

class CategorieQuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $content = file_get_contents('src/DataFixtures/data/CategorieQuestion.json');
        $json = json_decode($content);
        foreach ($json as $category) {
            /*
            $description = null;
            if (faker()->boolean(50)) {
                $description = faker()->text(50);
            }*/
            CategorieQuestionFactory::createOne([
                'Nom' => $category->{'Nom'},
                'description' => $category->{'description'},
            ]);
        }
        $manager->flush();
    }
}
