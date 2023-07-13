<?php

namespace App\Tests\Controller\Questionnaire;

use App\Entity\Questionnaire;
// use App\Entity\Reponse;
// use App\Entity\User;
use App\Factory\QuestionnaireFactory;
use App\Factory\ReponseFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function _before(ControllerTester $I)
    {
    }

    // tests
    /*
    public function verifiesIndexPageAndNumberOfElement(ControllerTester $I)
    {
        // On va sur la page des questionnaires
        $I->amOnPage('/questionnaire');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Hello QuestionnaireController!');
        // On vérifie le nom de l'en-tête des ressources
        $I->see('Les questionnaires ✅', 'h1');

        // On vérifie le nombre d'éléments de la page des ressources
        // On crée 10 ressources
        QuestionnaireFactory::createMany(10, ['Nom' => 'Questionnaire', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []]);
        $I->seeNumberOfElements('div.questionnaire', 10);
    }


    public function reachASpecificTopic(ControllerTester $I)
    {
        // On crée un sujet avec un nom spécifique
        $I->amOnPage('/questionnaire');
        $I->seeResponseCodeIsSuccessful();
        QuestionnaireFactory::createOne(['Nom' => 'test', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []]);

        // On vérifie que l'accès au sujet spécifique fonctionne bien
        $I->click(
            'a[href="/questionnaire/test"]'
        );
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('test');
        $I->see('test', 'h2');
    }
    */
    public function testIndexReturnsSuccessfulResponse(ControllerTester $I)
    {
        $I->amOnPage('/questionnaire');
        QuestionnaireFactory::createMany(1, ['Nom' => 'Questionnaire 1', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []]);
        $I->seeResponseCodeIsSuccessful();
    }

    public function testDisplayNewQuestionnaireInList(ControllerTester $I)
    {
        // On crée un questionnaire avec une image de présentation
        $image = file_get_contents(__DIR__.'/../../../public/img/vignup.png');
        $questionnaireData = ['Nom' => 'Questionnaire 1', 'imagePresentation' => $image];

        // On insère le questionnaire dans la base de données
        $I->haveInRepository(Questionnaire::class, $questionnaireData);

        // On va sur la page d'index des questionnaires
        $I->amOnPage('/questionnaire');

        // On vérifie que la page est accessible et que le template est bien rendu
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Nos questionnaires');
        $I->see('Voici nos questionnaires !', 'h1');

        // On vérifie que le questionnaire est affiché dans la liste
        $I->see('Questionnaire 1', '//div[@class="questionnaire"]');
    }

    public function testIndexLoadsImagesForQuestionnaire(ControllerTester $I)
    {
        // On crée un questionnaire avec une image de présentation
        $image = file_get_contents(__DIR__.'/../../../public/img/vignup.png');
        $questionnaireData = ['Nom' => 'Questionnaire 1', 'imagePresentation' => $image];

        // On insère le questionnaire dans la base de données
        $I->haveInRepository(Questionnaire::class, $questionnaireData);

        // On va sur la page d'index des questionnaires
        $I->amOnPage('/questionnaire');

        // On vérifie que la page est accessible et que le template est bien rendu
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Nos questionnaires');
        $I->see('Voici nos questionnaires !', 'h1');

        // On vérifie que l'image du questionnaire est bien affichée
        $I->seeElement('img[src*="data:image/png"]');
    }

    /*
        à bouger dans les tests showresultquestionnaire
        public function testIndexDisplaysQuestionnaireResults(ControllerTester $I)
        {
            // On crée un utilisateur et un questionnaire avec des réponses associées
            $user = UserFactory::createOne(['email' => 'user@example.com']);
            $questionnaire = QuestionnaireFactory::createOne(['Nom' => 'Questionnaire 1']);
            $reponses = ReponseFactory::createMany(3, ['questionnaire' => $questionnaire, 'user' => $user]);

            // On insère l'utilisateur, le questionnaire et les réponses dans la base de données
            $I->haveInRepository(User::class, $user);
            $I->haveInRepository(Questionnaire::class, $questionnaire);
            $I->haveInRepository(Reponse::class, $reponses);

            // On va sur la page d'index des questionnaires
            $I->amOnPage('/questionnaire');

            // On vérifie que la page est accessible et que le template est bien rendu
            $I->seeResponseCodeIsSuccessful();
            $I->seeInTitle('Hello QuestionnaireController!');
            $I->see('Les questionnaires ✅', 'h1');

            // On calcule la note totale des réponses
            $noteTotale = array_reduce($reponses, function ($total, $reponse) {
                return $total + $reponse->getNote();
            }, 0);

            // On vérifie que la note totale est bien affichée pour le questionnaire
            $I->see("Note totale : $noteTotale", "//div[@class='questionnaire' and contains(., 'Questionnaire 1')]");
        }*/
    public function testOnlyAdminCanSeeEditAndDeleteLinks(ControllerTester $I)
    {
        // On crée un utilisateur ordinaire
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'user', 'roles' => ['ROLE_USER']]);
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');
        // On crée un questionnaire
        $questionnaire = QuestionnaireFactory::createOne([]);
        // On va sur la page d'index des questionnaires
        $I->amOnPage('/questionnaire');
        // On vérifie que la page est accessible
        $I->seeResponseCodeIsSuccessful();
        // On vérifie que les liens de modification et suppression ne sont pas affichés pour l'utilisateur ordinaire
        $I->dontSee('Modifier', 'div.admin_link');
        $I->dontSee('Supprimer', 'div.admin_link');

        // On crée un utilisateur administrateur
        UserFactory::createOne(['email' => 'root2@example.com', 'pseudo' => 'admin', 'roles' => ['ROLE_ADMIN']]);
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root2@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');
        // On va sur la page d'index des questionnaires
        $I->amOnPage('/questionnaire');
        // On vérifie que la page est accessible
        $I->seeResponseCodeIsSuccessful();
        // On vérifie que les liens de création et suppression sont affichés pour l'utilisateur administrateur
        // $I->seeLink('Modifier', '/questionnaire/'.$questionnaire->getId().'/update');
        $I->see('Modifier', 'div.admin_link');
        $I->see('Supprimer', 'div.admin_link');
    }

    /*
        public function testIndexSortsQuestionnairesByName(ControllerTester $I)
        {
            // On crée des questionnaires avec des noms différents
            QuestionnaireFactory::createMany(3, [
                ['Nom' => 'Questionnaire A', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []],
                ['Nom' => 'Questionnaire C', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []],
                ['Nom' => 'Questionnaire B', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []],
            ]);

            // On va sur la page d'index des questionnaires
            $I->amOnPage('/questionnaire');
            $I->seeResponseCodeIsSuccessful();

            // On vérifie que les questionnaires sont affichés dans l'ordre alphabétique
            $I->seeInOrder(['Questionnaire A', 'Questionnaire B', 'Questionnaire C'], '//div[@class="questionnaire"]');
        }
    */
    public function testIndexIsAccessibleForNonAuthenticatedUsers(ControllerTester $I)
    {
        $I->logout();
        $I->amOnPage('/questionnaire');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Nos questionnaires');
    }

    public function testQuestionnaireAccessibleUser(ControllerTester $I)
    {
        $I->amOnPage('/questionnaire');

        $questionnaire = QuestionnaireFactory::createOne(['Nom' => 'Questionnaire pour les utilisateurs', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []]);

        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'user', 'roles' => ['ROLE_USER']]);
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        $I->amOnPage('/questionnaire');
        $I->see($questionnaire->getNom(), 'div.questionnaire');
        $I->click('Questionnaire pour les utilisateurs');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/questionnaire/1');
    }

    public function testQuestionnaireAccessibleAdmin(ControllerTester $I)
    {
        $I->amOnPage('/questionnaire');

        $questionnaire = QuestionnaireFactory::createOne(['Nom' => 'Questionnaire pour les administrateurs', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_ADMIN'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []]);

        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'user', 'roles' => ['ROLE_ADMIN']]);
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        $I->amOnPage('/questionnaire');
        $I->see($questionnaire->getNom(), 'div.questionnaire');
        $I->click('Questionnaire pour les administrateurs');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/questionnaire/1');
    }

    public function testQuestionnaireOnlyAdmin(ControllerTester $I)
    {
        $I->amOnPage('/questionnaire');

        $questionnaire = QuestionnaireFactory::createOne(['Nom' => 'Questionnaire pour les administrateurs', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_ADMIN'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []]);

        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'user', 'roles' => ['ROLE_USER']]);
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        $I->amOnPage('/questionnaire');
        $I->see($questionnaire->getNom(), 'div.questionnaire');
        $I->click('Questionnaire pour les administrateurs');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/login');
    }

    public function testQuestionnaireOnlyUserAndAbove(ControllerTester $I)
    {
        $I->amOnPage('/questionnaire');

        $questionnaire = QuestionnaireFactory::createOne(['Nom' => 'Questionnaire pour les utilisateurs', 'partieConnecte' => 1, 'roleConnecte' => ['ROLE_USER'], 'description' => 'Description', 'imagePresentation' => 'imagePresentation', 'Questions' => [], 'conseils' => []]);

        $I->logout();

        $I->amOnPage('/questionnaire');
        $I->see($questionnaire->getNom(), 'div.questionnaire');
        $I->click('Questionnaire pour les utilisateurs');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/login');
    }

    public function testQuestionnairesArePubliclyAccessible(ControllerTester $I)
    {
        // On va sur la page des questionnaires
        $I->amOnPage('/questionnaire');

        // On crée un questionnaire
        $questionnaire = QuestionnaireFactory::createOne(['partieConnecte' => 0, 'roleConnecte' => [], 'Nom' => 'Questionnaire public', 'imagePresentation' => 'imagePresentation', 'description' => 'Description', 'Questions' => [], 'conseils' => []]);

        $I->logout();
        // On vérifie que la page est accessible et que le template est bien rendu
        $I->amOnPage('/questionnaire');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Nos questionnaires');
        $I->see('Voici nos questionnaires !', 'h1');

        // On vérifie que le questionnaire est affiché dans la liste
        $I->see($questionnaire->getNom(), 'div.questionnaire');
        $I->click('Questionnaire public');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/questionnaire/1');
    }
}