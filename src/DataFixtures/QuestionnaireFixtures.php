<?php

namespace App\DataFixtures;

use App\Factory\QuestionnaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionnaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $content = file_get_contents('src/DataFixtures/data/QuestionnaireAutoEvaluation.json');
        $json = json_decode($content);
        foreach ($json as $libelle) {
            $questionnaire = $libelle->{'Questionnaire'};
            QuestionnaireFactory::createOne([
                'Nom' => $questionnaire->{'Nom'},
                'Description' => $questionnaire->{'Description'},
                'partieConnecte' => $questionnaire->{'partieConnecte'},
                'roleConnecte' => $questionnaire->{'roleConnecte'},
            ]);
        }
        QuestionnaireFactory::createMany(1);
        $manager->flush();
    }
}
