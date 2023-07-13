<?php

namespace App\Tests\Controller\Forum;

use App\Factory\SujetFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function verifiesIndexPageAndNumberOfElement(ControllerTester $I)
    {
        // On crée 10 sujets différents
        SujetFactory::createMany(10);
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Le Forum');
        $I->see('Forum', '.foru__titre');

        // On vérifie qu'il y a bien 10 sujets (+1 pour l'en-tête)
        $I->seeNumberOfElements('tr', 11);
    }

//    public function verifiesIfSearchedCorrectlyByTitle(ControllerTester $I)
//    {
//        // On crée 4 sujets
//        SujetFactory::createOne(['titre' => 'aaa']);
//        SujetFactory::createOne(['titre' => 'aab']);
//        SujetFactory::createOne(['titre' => 'aac']);
//        SujetFactory::createOne(['titre' => 'bbb']);
//        $I->amOnPage('/forum');
//        $I->seeResponseCodeIsSuccessful();
//
//        // On séléctionne le tri par titres
//        $I->fillField('topic_search', 'aa');
//        $I->click('#sub');
//
//        // On vérifie qu'on a bien seulement 3 sujets pour les titres dont
//        // le nom contient "aa"
//        $I->seeNumberOfElements('td', 9);
//    }
//
//    public function verifiesIfSearchedCorrectlyByAuthor(ControllerTester $I)
//    {
//        // On crée 4 sujets différents
//        SujetFactory::createOne(['titre' => 'aaa', 'user' => UserFactory::createOne(['pseudo' => 'Aaron'])]);
//        SujetFactory::createOne(['titre' => 'aab', 'user' => UserFactory::createOne(['pseudo' => 'ron'])]);
//        SujetFactory::createOne(['titre' => 'aac', 'user' => UserFactory::createOne(['pseudo' => 'dart'])]);
//        SujetFactory::createOne(['titre' => 'bbb', 'user' => UserFactory::createOne(['pseudo' => 'marc'])]);
//        $I->amOnPage('/forum');
//        $I->seeResponseCodeIsSuccessful();
//
//        // On séléctionne le tri par auteurs
//        $I->selectOption('search_type', 'author');
//        $I->fillField('topic_search', 'on');
//        $I->click('#sub');
//
//        // On vérifie qu'on a bien seulement 3 sujets pour les auteurs
//        // le nom contient "on"
//        $I->seeNumberOfElements('td', 6);
//    }

    public function verifiesIfPublishFunctionWorksWhenNotLoggedIn(ControllerTester $I)
    {
        // On essaye de créer un sujet sans être connecté
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('#sujet_titre', 'test');
        $I->fillField('#sujet_messages_0_contenu', 'test');
        $I->click('#pub');

        // On vérifie si on est bien redirigé vers la page de connexion dans ce cas
        $I->see('Connexion', 'h1.login-title');
    }

    public function verifiesIfPublishFunctionWorks(ControllerTester $I)
    {
        // On crée un utilisateur sur lequel on va se connecter
        UserFactory::createOne(['email' => 'root@example.com', 'pseudo' => 'tester']);

        // On va sur la page de connexion et on se connecte à l'utilisateur créé
        $I->amOnPage('/login');
        $I->see('Connexion', 'h1.login-title');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'test');
        $I->click('//button[@id="log"]');

        // On vérifie que la connexion est bel est bien effectuée
        $I->seeInTitle("Vign'UP");

        // On teste si la publication est possible
        $I->amOnPage('/forum');
        $I->seeInTitle('Le Forum');
        $I->fillField('#sujet_titre', 'test');
        $I->fillField('#sujet_messages_0_contenu', 'test');
        $I->click('#pub');

        // On vérifie que le sujet est bel et bien créé et qu'on est dessus
        $I->seeInTitle('Un sujet');
        $I->see('Sujet : test', 'h1');
    }

    public function reachASpecificTopic(ControllerTester $I)
    {
        // On crée un sujet avec un nom spécifique
        SujetFactory::createOne(['titre' => 'test']);
        $I->amOnPage('/forum');
        $I->seeResponseCodeIsSuccessful();
        $I->click('test');

        // On vérifie que l'accès au sujet spécifique fonctionne bien
        $I->seeInTitle('Un sujet');
        $I->see('Sujet : test', 'h1');
    }
}
