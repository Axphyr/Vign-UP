<?php

namespace App\DataFixtures;

use App\Factory\CategorieQuestionFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionnaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use function Zenstruck\Foundry\faker;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $content = file_get_contents('src/DataFixtures/data/QuestionnaireAutoEvaluation.json');
        $json = json_decode($content);
        foreach ($json as $libelle) {
            $questions = $libelle->{'Questions'};
            foreach ($questions as $question) {
                QuestionFactory::createOne([
                    'txtQuestion' => $question->{'txtQuestion'},
                    'categorieQuestion' => is_null($question->{'categorieQuestion'}) ? null : CategorieQuestionFactory::findBy(['id' => $question->{'categorieQuestion'}])[0],
                    'questionnaire' => QuestionnaireFactory::find(['id' => 1]),
                    'numero' => $question->{'numero'},
                ]);
            }
        }

        $questionnaireAll = QuestionnaireFactory::findBy(['id' => 2]);
        $numero = [];
        for ($i = 0; $i < count($questionnaireAll); ++$i) {
            $numero[$i] = 1;
        }
        for ($i = 0; $i < 35; ++$i) {
            $index = faker()->numberBetween(0, count($questionnaireAll) - 1);
            $questionnaire = $questionnaireAll[$index];
            $catQuestion = null;
            if (faker()->boolean(70)) {
                $catQuestion = CategorieQuestionFactory::random();
            }
            QuestionFactory::createOne([
                'questionnaire' => $questionnaire,
                'numero' => $numero[$index],
                'categorieQuestion' => $catQuestion,
            ]);
            ++$numero[$index];
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionnaireFixtures::class,
            CategorieQuestionFixtures::class,
        ];
    }
}
