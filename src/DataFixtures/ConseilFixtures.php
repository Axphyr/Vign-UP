<?php

namespace App\DataFixtures;

use App\Factory\CategorieQuestionFactory;
use App\Factory\ConseilFactory;
use App\Factory\QuestionnaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use function Zenstruck\Foundry\faker;

class ConseilFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $content = file_get_contents('src/DataFixtures/data/QuestionnaireAutoEvaluation.json');
        $json = json_decode($content);
        foreach ($json as $libelle) {
            $conseils = $libelle->{'Conseils'};
            foreach ($conseils as $conseil) {
                ConseilFactory::createOne([
                    'txtConseil' => $conseil->{'txtConseil'},
                    'noteMinimale' => $conseil->{'noteMinimale'},
                    'categorieQuestion' => is_null($conseil->{'categorieQuestion'}) ? null : CategorieQuestionFactory::findBy(['id' => $conseil->{'categorieQuestion'}])[0],
                    'partieConnecte' => $conseil->{'partieConnecte'},
                    'questionnaire' => QuestionnaireFactory::find(['id' => 1]),
                ]);
            }
        }

        for ($i = 0; $i < 2; ++$i) {
            ConseilFactory::createMany(6, function () {
                $questionnaire = QuestionnaireFactory::find(['id' => 2]);
                $partieConnecte = false;
                if (is_null($questionnaire->getPartieConnecte()) || 0 == $questionnaire->getPartieConnecte()) {
                    $partieConnecte = null;
                }
                $categorieQuestion = null;
                if (faker()->boolean(50)) {
                    $categorieQuestion = CategorieQuestionFactory::random();
                    $partieConnecte = true;
                }
                $tabNoteCat = $questionnaire->getNoteTotalForEachCategorieQuestion();

                return [
                    'noteMinimale' => is_null($categorieQuestion)
                        ? faker()->unique()->numberBetween(0, $questionnaire->getNoteTotal())
                        : faker()->unique()->numberBetween(0, $tabNoteCat[$categorieQuestion->getNom()]),
                    'questionnaire' => $questionnaire,
                    'partieConnecte' => $partieConnecte,
                    'categorieQuestion' => $categorieQuestion,
                ];
            });
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionnaireFixtures::class,
            QuestionFixtures::class,
        ];
    }
}
