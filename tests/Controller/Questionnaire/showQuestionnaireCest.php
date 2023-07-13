<?php

namespace App\Tests\Controller\Questionnaire;

use App\Factory\QuestionFactory;
use App\Factory\QuestionnaireFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class showQuestionnaireCest
{
    public function testShowQuestionnairePageIsAccessible(ControllerTester $I)
    {
        // On crée des questions avec un questionnaire lié
        $questionnaire = QuestionnaireFactory::createOne();
        $questions = QuestionFactory::createMany(2, ['questionnaire' => $questionnaire, 'numero' => 1]);

        // On va sur la page du questionnaire
        $I->amOnPage("/questionnaire/{$questionnaire->getId()}");

        // On vérifie que la page est accessible
        $I->seeResponseCodeIsSuccessful();

        // On vérifie que le nom du questionnaire est bien affiché
        $I->see("{$questionnaire->getNom()}", 'h1');

        // On vérifie que la page contient les éléments attendus
        $I->see('Question n°1/2', '//div[@class="questionnaire_question"]');
        $I->see('Valider vos réponses', '//button[@id="submit1"]');
    }

    public function testShowQuestionnairePageIsNotAccessibleWithoutCorrectRole(ControllerTester $I)
    {
        // On crée des questions

        // On crée un questionnaire qui nécessite le rôle 'ROLE_ADMIN' pour y accéder
        $questionnaire = QuestionnaireFactory::createOne([
            'Nom' => 'Questionnaire 1',
            'partieConnecte' => 1,
            'roleConnecte' => ['ROLE_ADMIN'],
            'description' => 'Description',
            'imagePresentation' => 'imagePresentation',
            'Questions' => [],
            'conseils' => [],
        ]);
        $questions = QuestionFactory::createMany(2, ['questionnaire' => $questionnaire, 'numero' => 1]);
        // On se connecte en tant qu'utilisateur normal (sans le rôle 'ROLE_ADMIN')
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'user', 'roles' => ['ROLE_USER']]);
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');
        // On va sur la page du questionnaire
        $I->amOnPage("/questionnaire/{$questionnaire->getId()}");

        // On vérifie que la page n'est pas accessible
        $I->seeResponseCodeIsServerError();
    }

    public function testShowQuestionnairePageIsNotAccessibleIfNotConnected(ControllerTester $I)
    {
        // On crée un questionnaire avec une partie qui nécessite une connexion
        $questionnaire = QuestionnaireFactory::createOne([
            'partieConnecte' => 1,
            'roleConnecte' => ['ROLE_USER'],
        ]);
        // il faut au moins une question dans le questionnaire
        $questions = QuestionFactory::createMany(2, ['questionnaire' => $questionnaire, 'numero' => 1]);

        // On va sur la page du questionnaire en étant déconnecté
        $I->logout();
        $I->amOnPage("/questionnaire/{$questionnaire->getId()}");

        // On vérifie que la page est accessible
        $I->seeResponseCodeIsSuccessful();
        // On vérifie que le message pour se connecter est affiché
        $I->see('Pour pouvoir commencer ce questionnaire, veuillez vous connecter. Vous pouvez malgré tout, valider vos réponses.', 'p');
    }

    public function testQuestionnaireQuestionsAreDisplayedInOrder(ControllerTester $I)
    {
        // On crée un questionnaire
        $questionnaire = QuestionnaireFactory::createOne();
        // On crée des questions en spécifiant leur numéro
        $nb = -1;
        $questions = QuestionFactory::createMany(5, function ($nb) use ($questionnaire) {
            ++$nb;

            return [
                'questionnaire' => $questionnaire,
                'numero' => $nb - 1,
            ];
        });

        // On se connecte en tant qu'utilisateur qui peut accéder au questionnaire
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'user', 'roles' => ['ROLE_USER']]);
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');
        // On va sur la page du questionnaire
        $I->amOnPage("/questionnaire/{$questionnaire->getId()}");

        // On vérifie que les questions sont affichées dans l'ordre
        for ($i = 1; $i <= 5; ++$i) {
            $I->see("Question n°{$i}/5", 'h2');
        }
    }
    /* test a deplacer dans showResult
    public function testQuestionnaireAdvicesAreDisplayed(ControllerTester $I)
    {
        // On crée un questionnaire avec des conseils
        $conseil = ConseilFactory::createMany(3);
        $questionnaire = QuestionnaireFactory::createOne([
            'partieConnecte' => 1,
            'roleConnecte' => ['ROLE_USER'],
            'conseils' => [
                $conseil[0],
                $conseil[1],
                $conseil[2],
            ],
        ]);
        $questions = QuestionFactory::createMany(3, ['questionnaire' => $questionnaire, 'numero' => 1]);
        // On va sur la page du questionnaire
        $I->amOnPage("/questionnaire/{$questionnaire->getId()}");

        // On vérifie que les conseils sont affichés sur la page
        $I->see("Conseil 1", 'p');
        $I->see("Conseil 2", 'p');
        $I->see("Conseil 3", 'p');
    }
    */

   /* public function testQuestionnaireNavigationButtons(ControllerTester $I)
    {
        // On crée un questionnaire
        $questionnaire = QuestionnaireFactory::createOne();
        // On crée des questions
        $questions = QuestionFactory::createMany(5, ['questionnaire' => $questionnaire, 'numero' => 1]);

        // On va sur la page du questionnaire
        $I->amOnPage("/questionnaire/{$questionnaire->getId()}");

        // On vérifie que les boutons de navigation sont présents
        $I->see('Valider vos réponses', '//button[@id="submit1"]');
        $I->see('Question précédente', '//button[@id="past#2"]');

        $I->see('Question suivante', '//div[@class="question_nextAndPast"]/a[@id="next"]');
        $I->click('Question suivante', '//div[@class="question_nextAndPast"]/a[@id="next"]');
        $I->seeCurrentRouteIs("/questionnaire/{$questionnaire->getId()}#2");
    }
   */
}
