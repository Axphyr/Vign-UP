<?php


namespace App\Tests\Controller\Prestataires;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function verifiesPageTitle(ControllerTester $I)
    {
        $I->amOnPage('/fournisseurs-prestataires');
        // On crée un prestataire
        UserFactory::createOne(['email' => 'ot@example.com', 'pseudo' => 'tester1', 'roles' => ['ROLE_PRESTATAIRE']]);
        // On va sur la page des prestataires
        $I->amOnPage('/fournisseurs-prestataires');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Page Fournisseurs & Prestataires');
        $I->see('Prestataires', 'h1');
    }

    public function verifiesIndexPageAndNumberOfElement(ControllerTester $I)
    {
        $I->amOnPage('/fournisseurs-prestataires');
        // On crée un prestataire
        UserFactory::createOne(['email' => 'ot@example.com', 'pseudo' => 'tester1', 'roles' => ['ROLE_PRESTATAIRE']]);

        // On va sur la page des prestataires
        $I->amOnPage('/fournisseurs-prestataires');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Page Fournisseurs & Prestataires');
        $I->see('Prestataires', 'h1');

        // On vérifie que le nombre de prestataires de la page corresponde bien
        $I->seeNumberOfElements('.prest-top', 1);
    }
/*
    public function searchAPerticularRole(ControllerTester $I)
    {
        $I->amOnPage('/fournisseurs-prestataires');
        UserFactory::createOne(['email' => 'rooot@example.com', 'pseudo' => 'fournisseur_test', 'roles' => ['ROLE_FOURNISSEUR']]);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page des prestataires
        $I->amOnPage('/fournisseurs-prestataires');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Page Fournisseurs & Prestataires');
        $I->see('Fournisseurs', 'h1');

        // On sélectionne le tri par fournisseurs
        $I->selectOption('search_type', 'fournisseurs');
        $I->fillField('user_search', 'four');
        $I->click('#sub');

        // On vérifie qu'on retrouve bien le fournisseur
        $I->seeNumberOfElements('.prest-top', 1);
    }
*/
}
