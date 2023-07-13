<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Sujet;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;

class SujetController extends AbstractController
{

    #[Route('/sujet/{id}/delete', name: 'app_topic_delete')]
    #[Security("is_granted('ROLE_ADMIN') or user.getId() == sujet.getUser().getId()", message: 'Accès interdit.', statusCode: 404)]
    public function delete(Request $request, ManagerRegistry $doctrine, Sujet $sujet, MessageRepository $repository): Response
    {
        dump($this->getUser()->getId());
        dump($sujet->getUser()->getId());
        $messages = $repository->findByTopic($sujet->getId());
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->get('delete')->isClicked()) {
            foreach ($messages as $message) {
                $doctrine->getManager()->remove($message);
            }
            $doctrine->getManager()->remove($sujet);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_forum');
        }
        if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('app_forum');
        }

        return $this->renderForm('sujet/delete.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }
}
