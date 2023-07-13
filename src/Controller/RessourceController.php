<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Form\RessourceType;
use App\Repository\RessourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RessourceController extends AbstractController
{
    #[Route('/ressource', name: 'app_ressource')]
    public function index(RessourceRepository $ressource): Response
    {
        $ressource = $ressource->findBy([], ['id' => 'DESC']);

        return $this->render('ressource/index.html.twig', [
            'controller_name' => 'RessourceController',
            'ressources' => $ressource,
        ]);
    }

    #[Route('/ressource/create', name: 'app_ressource_create')]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(RessourceType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $ressource = $form->getData();
            $doctrine->getManager()->persist($ressource);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_ressource');
        } elseif ($form->isSubmitted() && $form->isValid() && !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('ressource/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/ressource/{id}/delete', name: 'app_ressource_delete', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function delete(Ressource $ressource, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->get('delete')->isClicked()) {
            $doctrine->getManager()->remove($ressource);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_ressource');
        }
        if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('app_ressource');
        }

        return $this->renderForm('ressource/delete.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }

    #[Route('/ressource/{id}/update', name: 'app_ressource_update', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function update(Ressource $ressource, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ressource = $form->getData();
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_ressource');
        }

        return $this->renderForm('ressource/update.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }
}
