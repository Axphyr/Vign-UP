<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Sujet;
use App\Form\MessageType;
use App\Form\SujetType;
use App\Repository\MessageRepository;
use App\Repository\SujetRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(ManagerRegistry $doctrine, SujetRepository $repository, Request $request): Response
    {
        $lastPage = false;
        $page = $request->query->get('page', 1);
        $search = $request->query->get('topic_search', '');
        $searchType = $request->query->get('search_type', 'title');
        if ('title' == $searchType) {
            $topics = $repository->searchByTitle($search, $page);
        } elseif ('author' == $searchType) {
            $topics = $repository->searchByAuthor($search, $page);
        }
        // Vérification de la dernière page
        if (0 == sizeof($repository->searchByTitle($search, $page + 1))) {
            $lastPage = true;
        }
        // Formulaire de création d'un sujet //
        $forum_sujet = new Sujet();
        $forum_message = new Message();
        $forum_sujet->addMessage($forum_message);
        $form = $this->createForm(SujetType::class, $forum_sujet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $sujet = $form->getData();
            $date = new \DateTime();
            $forum_message->setDateMessage($date);
            $forum_message->setUser($this->getUser());
            $sujet->setDateCreation($date);
            $sujet->setUser($this->getUser());
            $sujet->setLastMessageDate($forum_message->getDateMessage());
            $doctrine->getManager()->persist($forum_message);
            $doctrine->getManager()->persist($sujet);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_forum_topic', ['id' => $sujet->getid()]);
        } elseif ($form->isSubmitted() && $form->isValid() && !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'topics' => $topics,
            'form' => $form->createView(),
            'page' => $page,
            'lastPage' => $lastPage,
        ]);
    }

    #[Route('/forum/sujet/{id}', name: 'app_forum_topic')]
    public function topic(UserRepository $us, MessageRepository $repository, Sujet $topic, Request $request, ManagerRegistry $doctrine): Response
    {
        $topicId = $topic->getId();
        $messages = $repository->findByTopic($topicId);

        $images = [];

        foreach ($messages as $msg) {
            $user = $msg->getUser();
            if (!is_null($user->getAvatar())) {
                $images[$msg->getId()] = base64_encode(stream_get_contents($user->getAvatar()));
            }
        }

        // Formulaire de création d'un message //
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $message = $form->getData();
            $message->setDateMessage(new \DateTime());
            $message->setUser($this->getUser());
            $message->setSujet($topic);
            $topic->setLastMessageDate($message->getDateMessage());
            $doctrine->getManager()->persist($message);
            $doctrine->getManager()->persist($topic);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_forum_topic', ['id' => $topic->getid()]);
        } elseif ($form->isSubmitted() && $form->isValid() && !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('forum/topic.html.twig', [
            'messages' => $messages,
            'topic' => $topic,
            'form' => $form->createView(),
            'images' => $images,
        ]);
    }
}
