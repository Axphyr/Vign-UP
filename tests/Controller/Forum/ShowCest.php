<?php

namespace App\Tests\Controller\Forum;

use App\Factory\MessageFactory;
use App\Factory\SujetFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class ShowCest
{
    public function verifiesIndexPageAndNumberOfElement(ControllerTester $I)
    {
        // On crée un sujet
        $x = SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée 3 messages que l'on associe au sujet créé
        MessageFactory::createOne(['contenu' => 'abcd', 'sujet' => $x]);
        MessageFactory::createOne(['contenu' => 'efgh', 'sujet' => $x]);
        MessageFactory::createOne(['contenu' => 'ijkl', 'sujet' => $x]);

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On vérifie que l'on a bien les 3 messages créés précedemment
        $I->seeNumberOfElements('.message-box', 3);
    }

    public function verifiesIfCreatingASpecificMessageWorks(ControllerTester $I)
    {
        // On crée un sujet
        $x = SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un message spécifique que l'on associe au sujet créé
        MessageFactory::createOne(['contenu' => 'abcd', 'sujet' => $x]);

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On vérifie que le message spécifique créé précédemment est bien présent et bon
        $I->see('abcd', '.message_content');
    }

    public function verifiesIfCreatingAMessageAsAUserWorks(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée le message en tant qu'utilisateur directement
        $I->fillField('#message_contenu', 'test message');
        $I->click('#mess_sub');

        // On vérifie que le message est bien présent et bon
        $I->see('test message', '.message_content');
    }

    public function verifiesIfSortedCorrectly(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée les messages en tant qu'utilisateur
        $I->fillField('#message_contenu', 'abcd');
        $I->click('#mess_sub');
        $I->fillField('#message_contenu', 'efgh');
        $I->click('#mess_sub');
        $I->fillField('#message_contenu', 'ijkl');
        $I->click('#mess_sub');

        // On récupère les messages
        $tmp = $I->grabMultiple('.message_content');

        // On regarde si c'est trié dans le bon sens
        $I->assertEquals(['abcd', 'efgh', 'ijkl'], $tmp);
    }

    public function verifiesIfUserIsCorrectlyDisplayed(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée le message en tant qu'utilisateur
        $I->fillField('#message_contenu', 'abcd');
        $I->click('#mess_sub');

        // On vérifie si le nom est le bon
        $I->see('tester', '.message__name');
    }

    // ATTENTION : Ne fonctionne pas car l'affichage de la date
    // est différent de celui de la base de donnée
    // Note : changer la date dans le test pour qu'il fonctionne
    /*
    public function verifiesIfDateIsCorrectlyDisplayed(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Me Connecter', 'h1');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée le message en tant qu'utilisateur
        $I->fillField('#message_contenu', 'abcd');
        $I->click('#mess_sub');

        // On regarde si la date affichée est la bonne
        $I->assertEquals($I->grabMultiple('.message__date'), ['18:44, le 20/12/2022']);
    }
    */

    public function verifiesIfDeleteButtonAndEditButtonAreHereIfTheOneCreatingTheMessageIsLoggedIn(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée le message en tant qu'utilisateur directement
        $I->fillField('#message_contenu', 'test message');
        $I->click('#mess_sub');

        // On vérifie que le message est bien présent et bon
        $I->see('test message', '.message_content');

        // On regarde si le bouton de suppression et de modification est présent
        $I->seeNumberOfElements('.message__suppr', 1);
        $I->seeNumberOfElements('.message__modif', 1);
    }

    public function verifiesIfRedirectedCorrectlyAfterPressingDeleteButton(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée le message en tant qu'utilisateur directement
        $I->fillField('#message_contenu', 'test message');
        $I->click('#mess_sub');

        // On vérifie que le message est bien présent et bon
        $I->see('test message', '.message_content');

        // On regarde si la redirection fonctionne
        $I->click('.message__suppr');
        $I->seeInTitle("Suppression d'un message");
    }

    public function verifiesIfDeleteAMessageWorks(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée les messages en tant qu'utilisateur directement
        $I->fillField('#message_contenu', 'test message');
        $I->click('#mess_sub');
        $I->fillField('#message_contenu', 'test message 2');
        $I->click('#mess_sub');
        $I->seeNumberOfElements('.message-box', 2);

        // On vérifie que le message est bien présent et bon
        $I->see('test message', '.message_content');

        // On regarde si la redirection fonctionne
        $I->click('delete');
        $I->seeInTitle("Suppression d'un message");

        // On clique sur le bouton de suppression
        $I->click('#form_delete');

        // On vérifie que l'on est redirigé vers le forum
        $I->seeInTitle('Le Forum');

        // On revient sur le sujet
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On regarde que le message est bien supprimé
        $I->seeNumberOfElements('.message-box', 1);
    }

    public function verifiesIfEditAMessageWorks(ControllerTester $I)
    {
        // On crée un sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On crée les messages en tant qu'utilisateur directement
        $I->fillField('#message_contenu', 'test message');
        $I->click('#mess_sub');

        // On vérifie que le message est bien présent et bon
        $I->see('test message', '.message_content');

        // On regarde si la redirection fonctionne
        $I->click('edit');
        $I->seeInTitle("Édition d'un message");

        // On modifie le message et on confirme
        $I->fillField('#message_contenu', 'nouveau message');
        $I->click('#form_update');

        // On vérifie que l'on est redirigé vers le sujet
        $I->see('Sujet : sujet test');

        // On regarde que le message est bien modifié
        $I->see('nouveau message', '.message_content');
    }

    public function verifiesIfDeleteATopicWorks(ControllerTester $I)
    {
        // On crée deux sujet
        SujetFactory::createOne(['titre' => 'sujet test']);
        SujetFactory::createOne(['titre' => 'sujet test 2']);

        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester', 'roles' => ['ROLE_ADMIN']]);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On regarde si la redirection fonctionne
        $I->click('.topic_button_supp');
        $I->seeInTitle("Suppression d'un sujet");

        // On clique sur le bouton de suppression
        $I->click('#form_delete');

        // On vérifie que l'on est redirigé vers le forum
        $I->seeInTitle('Le Forum');

        // On regarde que le sujet est bien supprimé (en-tête + sujet 2)
        $I->seeNumberOfElements('tr', 2);
    }

    public function verifiesIfRedirectionToForumButtonWorks(ControllerTester $I)
    {
        // On crée deux sujet
        SujetFactory::createOne(['titre' => 'sujet test']);

        // On va sur la page du forum
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On clique sur le sujet précédemment créé
        $I->click('sujet test');
        $I->see('Sujet : sujet test', 'h1');

        // On clique sur le bouton de retour vers le forum
        $I->click('Retour à la liste des sujets');

        // On vérifie que la redirection a fonctionée
        $I->seeInTitle('Le Forum');
    }
}
