<?php

namespace App\Tests\Controller\Ressource;

use App\Factory\RessourceFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function verifiesIndexPageAndNumberOfElement(ControllerTester $I): void
    {
        // On crée 10 ressources
        RessourceFactory::createMany(10);

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On vérifie le nom de l'en-tête des ressources
        $I->see('Ressources', '.ressource__header');

        // On vérifie le nombre d'éléments de la page des ressources
        $I->seeNumberOfElements('.ressources', 10);
    }

    public function createSpecificObjectNom(ControllerTester $I): void
    {
        // On crée une ressource avec un nom spécifique
        RessourceFactory::createOne(['nomRessource' => 'RessourceTest']);

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On regarde si on a bien une ressource avec le nom spécifique
        $I->see('RessourceTest', '.ressources__nom');
    }

    public function createSpecificObjectDesc(ControllerTester $I): void
    {
        // On crée une ressource avec un nom spécifique
        RessourceFactory::createOne(['descriptif' => 'RessourceTest']);

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On regarde si on a bien une ressource avec la description spécifique
        $I->see('RessourceTest', '.ressources__desc');
    }

    public function verifiesIfSortedCorrectly(ControllerTester $I)
    {
        // On crée deux ressources
        RessourceFactory::createOne(['nomRessource' => 'premier']);
        RessourceFactory::createOne(['nomRessource' => 'deuxième']);

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On regarde si les ressources sont bien triées
        $I->assertEquals($I->grabMultiple('.ressources__nom'), ['Deuxième', 'Premier']);
    }

    public function verifiesIfUpdateAndDeleteButtonAreHereWhenLoggedInAsAnAdministrator(ControllerTester $I)
    {
        // On crée un certain nombre de ressources
        RessourceFactory::createMany(5);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On regarde si les bouttons de modification et de suppression sont présents
        $I->see('', '.ressource__delete');
        $I->see('', '.ressource__update');
    }

    public function verifiesIfRedirectedCorrectlyAfterPressingDeleteButton(ControllerTester $I)
    {
        // On crée une ressource
        RessourceFactory::createOne(['nomRessource' => 'ressource test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On clique sur le bouton de suppression de la ressource précédemment créé
        $I->click('.ressource__delete');

        // On vérifie que l'on est bien sur la page de suppression de cette actualité
        $I->seeInTitle("Suppression d'une ressource");
        $I->see('Supprimer la ressource ressource test', 'p');
    }

    public function verifiesIfDeleteAResourceWorks(ControllerTester $I)
    {
        // On crée deux ressources
        RessourceFactory::createOne(['nomRessource' => 'ressource test']);
        RessourceFactory::createOne(['nomRessource' => 'ressource test 2']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On clique sur le bouton de suppression de la ressource précédemment créé
        $I->click('.ressource__delete');

        // On vérifie que l'on est bien sur la page de suppression de cette actualité
        $I->seeInTitle("Suppression d'une ressource");
        $I->see('Supprimer la ressource ressource test', 'p');

        // On clique sur le bouton de suppression
        $I->click('#form_delete');

        // On vérifie que la ressource a bien été supprimée
        $I->seeNumberOfElements('.ressources', 1);
    }

    public function verifiesIfUpdateANewsWorks(ControllerTester $I)
    {
        // On crée une actualités
        RessourceFactory::createOne(['nomRessource' => 'ressource test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page des ressources
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On clique sur le bouton de modification de la ressource précédemment créé
        $I->click('.ressource__update');

        // On vérifie que l'on est bien sur la page de suppression de cette ressource
        $I->seeInTitle("Modification d'une ressource");
        $I->see('Modification de la ressource ressource test', 'p');

        // On modifie le titre de la ressource
        $I->fillField('#ressource_nomRessource', 'Modification.');

        // On confirme la modification
        $I->click('#form_update');

        // On vérifie que la ressource a bien été modifié
        $I->see('Modification.', '.ressources__nom');
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

        // On va sur la page des ressource
        $I->amOnPage('/ressource');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Vign'UP");

        // On va sur la page de création d'une ressource
        $I->amOnPage('/ressource/create');

        // On remplit les inforamtion de la ressource à créer
        $I->fillField('#ressource_typeRessource', 'test');
        $I->fillField('#ressource_nomRessource', 'Test de création ressource');
        $I->fillField('#ressource_descriptif', 'Description de test de création ressource');
        $I->fillField('#ressource_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab_channel=RickAstley');

        // On clique sur le bouton pour confirmer la création de la ressource
        $I->click('Créer');

        // On vérifie que l'on est bien sur la page des ressources
        $I->seeInTitle("Vign'UP");

        // On vérifie que la ressource créée manuellement est présente
        $I->see('Test de création ressource', '.ressources__nom');
        $I->see('Description de test de création ressource', '.ressources__desc');
    }
}
