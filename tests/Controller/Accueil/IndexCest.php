<?php

namespace App\Tests\Controller\Accueil;

use App\Factory\ActualiteFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function verifiesIndexPageAndNumberOfElement(ControllerTester $I)
    {
        // On crée 10 actualités
        ActualiteFactory::createMany(10);

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");
        $I->see('Dernières actualités', '.actualite__header');

        // On vérifie que le nombre d'actualités de la page corresponde bien
        $I->seeNumberOfElements('.actualite', 10);
    }

    public function verifiesQuestionnaireButton(ControllerTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->click('Questionnaire');
        $I->seeCurrentRouteIs('app_questionnaire');
    }

    public function verifiesRessourcesButton(ControllerTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->click('Ressources');
        $I->seeCurrentRouteIs('app_ressource');
    }

    public function verifiesForumButton(ControllerTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->click('Forum');
        $I->seeCurrentRouteIs('app_forum');
    }

    public function createSpecificObject(ControllerTester $I)
    {
        // On crée une actualité avec un intitulé spécifique
        ActualiteFactory::createOne(['intitule' => 'actualiteTest']);

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();

        // On vérifie que l'actualité spécifique est créée
        $I->see('actualiteTest', '.actualite__intitule');
    }

    public function verifiesIfSortedCorrectly(ControllerTester $I)
    {
        // On crée 2 actualités différentes
        ActualiteFactory::createOne(['intitule' => 'premier', 'date' => new \DateTimeImmutable('now')]);
        // On attend 1 seconde pour que les dates des deux actualités soient différentes
        sleep(1);
        ActualiteFactory::createOne(['intitule' => 'deuxième', 'date' => new \DateTimeImmutable('now')]);

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On regarde si c'est bien trié correctement
        $I->assertEquals($I->grabMultiple('.actualite__intitule'), ['Deuxième', 'Premier']);
    }

    public function verifiesIfUpdateAndDeleteButtonAreHereWhenLoggedInAsAnAdministrator(ControllerTester $I)
    {
        // On crée un certain nombre d'actualités
        ActualiteFactory::createMany(5);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On regarde si les bouttons de modification et de suppression sont présents
        $I->see(' Supprimer', '.actualite__delete');
        $I->see(' Modifier', '.actualite__update');
    }

    public function verifiesIfRedirectedCorrectlyAfterPressingDeleteButton(ControllerTester $I)
    {
        // On crée une actualité
        ActualiteFactory::createOne(['intitule' => 'actualite test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On clique sur le bouton de suppression de l'actualité précédemment créé
        $I->click('Supprimer');

        // On vérifie que l'on est bien sur la page de suppression de cette actualité
        $I->seeInTitle("Suppression d'une actualité");
        $I->see("Supprimer l'actualité actualite test", 'p');
    }

    public function verifiesIfDeleteANewsWorks(ControllerTester $I)
    {
        // On crée deux actualités
        ActualiteFactory::createOne(['intitule' => 'actualite test']);
        ActualiteFactory::createOne(['intitule' => 'actualite test 2']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On clique sur le bouton de suppression de l'actualité précédemment créé
        $I->click('Supprimer');

        // On vérifie que l'on est bien sur la page de suppression de cette actualité
        $I->seeInTitle("Suppression d'une actualité");
        $I->see("Supprimer l'actualité actualite test", 'p');

        // On supprime l'actualité
        $I->click('#form_delete');

        // On vérifie que l'actualité a bien été supprimée
        $I->seeNumberOfElements('.actualite', 1);
    }

    public function verifiesIfUpdateANewsWorks(ControllerTester $I)
    {
        // On crée une actualités
        ActualiteFactory::createOne(['intitule' => 'actualite test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On clique sur le bouton de modification de l'actualité précédemment créé
        $I->click('.actualite__update');

        // On vérifie que l'on est bien sur la page de suppression de cette actualité
        $I->seeInTitle("Modification d'une actualité");
        $I->see("Modification de l'actualité actualite test", 'p');

        // On modifie le titre de l'actualité
        $I->fillField('#actualite_intitule', 'Modification.');

        // On confirme la modification
        $I->click('#form_update');

        // On vérifie que l'actualité a bien été modifié
        $I->see('Modification.', '.actualite__intitule');
    }

    public function verifiesIfCreatingManuallyANewsWorks(ControllerTester $I)
    {
        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page d'accueil
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On va sur la page de création d'une actualité
        $I->amOnPage('/actualite/create');

        // On remplit les inforamtion de l'actualité à créer
        $I->fillField('#actualite_type', 'test');
        $I->fillField('#actualite_intitule', 'Test de création actualité');
        $I->fillField('#actualite_description', 'Description de test de création actualité');

        // On clique sur le bouton pour confirmer la création de l'actualité
        $I->click('Créer');

        // On vérifie que l'on est bien sur la page d'accueil
        $I->seeInTitle("Vign'UP");

        // On vérifie que l'actualité créée manuellement est présente
        $I->see('Test de création actualité', '.actualite__intitule');
        $I->see('Description de test de création actualité', '.actualite__desc');
    }
}
