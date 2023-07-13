<?php

namespace App\Controller\Admin;

use App\Entity\Conseil;
use App\Entity\Message;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Sujet;
use App\Entity\Temoignage;
use App\Entity\User;
use App\Entity\VSL;
use App\Form\VSLType;
use App\Repository\VSLRepository;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/vsl', name: 'admin_vsl')]
    public function vsl(VSLRepository $repository): Response
    {
        $vsl = $repository->findByApprovedValue(0);
        return $this->render('admin/vsl.html.twig', [
            'vsl' => $vsl
        ]);
    }

    #[Route('/admin/vsl/{id}/accept', name: 'admin_vsl_accept', requirements: ['id' => '\d+'])]
    public function vslAccept(VSLRepository $repository, Request $request, ManagerRegistry $doctrine, VSL $vsl): Response
    {
        $form = $this->createForm(VSLType::class, $vsl)
            ->add('accept', SubmitType::class, ['label' => 'Valider'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->get('accept')->isClicked()) {
            $vsl = $form->getData();
            $vsl->setApproved(1);
            $doctrine->getManager()->persist($vsl);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('admin_vsl');
        } elseif ($form->isSubmitted() && $form->isValid() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('admin_vsl');
        }
        return $this->render('admin/vslaccept.html.twig', [
            'vsl' => $vsl,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/vsl/{id}/refuse', name: 'admin_vsl_refuse', requirements: ['id' => '\d+'])]
    public function vslRefuse(VSLRepository $repository, Request $request, ManagerRegistry $doctrine, VSL $vsl): Response
    {

        $form = $this->createForm(VSLType::class, $vsl)
            ->add('delete', SubmitType::class, ['label' => 'Supprimer le point'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->get('delete')->isClicked()) {
            $vsl = $form->getData();
            $doctrine->getManager()->remove($vsl);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('admin_vsl');
        } elseif ($form->isSubmitted() && $form->isValid() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('admin_vsl');
        }
        return $this->render('admin/vslrefuse.html.twig', [
            'vsl' => $vsl,
            'form' => $form->createView(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle("Espace Administrateur Vign'Up");
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Sujets Forum', 'fas fa-list', Sujet::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-list', Message::class);
        yield MenuItem::linkToCrud('Questions Questionnaire', 'fas fa-list', Question::class);
        yield MenuItem::linkToCrud('Réponses Questionnaire', 'fas fa-list', Reponse::class);
        yield MenuItem::linkToCrud('Conseils', 'fas fa-list', Conseil::class);
        yield MenuItem::linkToCrud('Témoignages', 'fas fa-list', Temoignage::class);
        yield MenuItem::linkToCrud('Points Carte VSL', 'fas fa-list', VSL::class);
    }
}
