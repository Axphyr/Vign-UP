<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message/{id}/delete', name: 'app_message_delete')]
    #[Security("is_granted('ROLE_ADMIN') or user.getId() == message.getUser().getId()", message: 'Accès interdit.', statusCode: 404)]

    public function delete(Request $request, ManagerRegistry $doctrine, Message $message): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->get('delete')->isClicked()) {
            $doctrine->getManager()->remove($message);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_forum');
        }
        if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('app_forum');
        }

        return $this->renderForm('message/delete.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/message/{id}/update', name: 'app_message_update', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN') or user.getId() == message.getUser().getId()", message: 'Accès interdit.', statusCode: 404)]
    public function update(Request $request, Message $message, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_forum_topic', ['id' => $message->getSujet()->getId()]);
        }

        return $this->renderForm('message/update.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }
}
