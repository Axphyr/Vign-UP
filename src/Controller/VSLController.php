<?php

namespace App\Controller;

use App\Form\VSLType;
use App\Repository\VSLRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VSLController extends AbstractController
{
    #[Route('/la-carte', name: 'app_vsl')]
    public function index(VSLRepository $repository, Request $request, ManagerRegistry $doctrine): Response
    {
        $submitted = 0;
        $points = $repository->findByApprovedValue(1);
        $form = $this->createForm(VSLType::class);
        $form->handleRequest($request);
        if($submitted==0) {
            if ($form->isSubmitted() && $form->isValid() && $this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $submitted = 1;
                $vsl = $form->getData();
                $vsl->setApproved(0);
                $doctrine->getManager()->persist($vsl);
                $doctrine->getManager()->flush();
            } elseif ($form->isSubmitted() && $form->isValid() && !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('vsl/index.html.twig', [
            'form' => $form->createView(),
            'vslList' => $points,
            'submitted' => $submitted
         ]);
    }
}
