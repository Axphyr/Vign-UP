<?php

namespace App\DataFixtures;

use App\Factory\QuestionFactory;
use App\Factory\QuestionnaireFactory;
use App\Factory\ReponseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReponseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $content = file_get_contents('src/DataFixtures/data/QuestionnaireAutoEvaluation.json');
        $json = json_decode($content);
        foreach ($json as $libelle) {
            $reponses = $libelle->{'Reponses'};
            foreach ($reponses as $reponse) {
                ReponseFactory::createOne([
                    'question' => QuestionFactory::findBy(['questionnaire' => QuestionnaireFactory::find(['id' => 1]), 'numero' => $reponse->{'numero'}])[0],
                    'txtReponse' => $reponse->{'txtReponse'},
                    'nombrePoints' => $reponse->{'nombrePoints'},
                ]);
            }
        }

        ReponseFactory::createMany(75, function () {
            $question = QuestionFactory::findBy(['questionnaire' => 2]);
            $alea = array_rand($question);

            return [
                'question' => $question[$alea],
            ];
        });
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,
            QuestionnaireFixtures::class,
        ];
    }
}
