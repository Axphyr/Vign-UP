<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use App\Repository\QuestionnaireRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[IsGranted('IS_AUTHENTICATED')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(QuestionnaireRepository $questionnaireRepository, UserRepository $userRepository, Request $request): Response
    {
        $page = $request->query->get('search', '');
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $users = $userRepository->search($page);

        list($tabReponsesUser, $questionnaires) = $questionnaireRepository->getReponsesOfUserForEachQuestionnaire($user);

        return $this->render('profile/index.html.twig', [
            'users' => $users,
            'search' => $page,
            'avatar' => is_null($user->getAvatar()) ? null : base64_encode(stream_get_contents($user->getAvatar())),
            'questionnaires' => $questionnaires,
            'tabReponsesUser' => $tabReponsesUser,
        ]);
    }

    #[Route('/profile/{id}', name: 'app_profile_show', requirements: ['id' => '\d+'])]
    public function show(QuestionnaireRepository $questionnaireRepository, User $user): Response
    {
        list($tabReponsesUser, $questionnaires) = $questionnaireRepository->getReponsesOfUserForEachQuestionnaire($user);

        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'avatar' => is_null($user->getAvatar()) ? null : base64_encode(stream_get_contents($user->getAvatar())),
            'questionnaires' => $questionnaires,
            'tabReponsesUser' => $tabReponsesUser,
        ]);
    }

    #[Route('/profile/modification', name: 'app_profile_modif')]
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($user);
            $doctrine->flush();

            $this->addFlash('message', 'Profil mis à jour');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/editprofile.html.twig', [
            'form' => $form->createView(),
            ]);
    }

    #[Route('/profile/modification/password', name: 'app_profile_modif_pass')]
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $doctrine = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $doctrine->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('app_profile');
            } else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('profile/editpass.html.twig');
    }

    #[Route('/profile/users', name: 'app_profile_users_search')]
    public function userSearch(UserRepository $userRepository, Request $request): Response
    {
        $page = $request->query->get('search', '');

        $users = $userRepository->search($page);

        return $this->render('profile/showallusers.html.twig', [
            'users' => $users,
            'search' => $page,
        ]);
    }
}
