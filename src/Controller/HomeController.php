<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Form\ImageType;
use App\Repository\ActualiteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ActualiteRepository $actualite, Request $request, ActualiteRepository $repository): Response
    {
        $lastPage = false;
        $page = $request->query->get('page', 1);

        $actuality = $actualite->searchByPage($page);

        if (0 == sizeof($repository->searchByPage($page + 1))) {
            $lastPage = true;
        }

        $images = [];

        foreach ($actuality as $act) {
            if (!is_null($act->getCover())) {
                $images[$act->getId()] = base64_encode(stream_get_contents($act->getCover()));
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'actuality' => $actuality,
            'images' => $images,
            'page' => $page,
            'lastPage' => $lastPage,
        ]);
    }

    #[Route('/actualite/{id}/update', name: 'app_actualite_update', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function update(Actualite $actualite, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $actualite = $form->getData();
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('home/update.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }

    #[Route('/actualite/create', name: 'app_actualite_create')]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ActualiteType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $actualite = $form->getData();
            $actualite->setDate(new \DateTime());
            $doctrine->getManager()->persist($actualite);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_home');
        } elseif ($form->isSubmitted() && $form->isValid() && !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/actualite/{id}/delete', name: 'app_actualite_delete', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function delete(Actualite $actualite, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->get('delete')->isClicked()) {
            $doctrine->getManager()->remove($actualite);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_home');
        }
        if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('home/delete.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }
}
