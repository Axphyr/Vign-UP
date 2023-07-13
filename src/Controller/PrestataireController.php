<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestataireController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    #[Route('/fournisseurs-prestataires', name: 'app_page_fournisseurs')]
    public function index(UserRepository $repository, Request $request): Response
    {
        $search = $request->query->get('user_search', '');
        $searchType = $request->query->get('search_type', 'both');
        $fournisseurAvatars=[];
        $prestataireAvatars=[];
        if ('fournisseurs' == $searchType) {
            $fournisseurs = $repository->findbyRole('["ROLE_FOURNISSEUR"]', $search);
            $prestataires = [];
        } elseif ('prestataires' == $searchType) {
            $fournisseurs = [];
            $prestataires = $repository->findbyRole('["ROLE_PRESTATAIRE"]', $search);
        } else {
            $fournisseurs = $repository->findbyRole('["ROLE_FOURNISSEUR"]', $search);
            $prestataires = $repository->findbyRole('["ROLE_PRESTATAIRE"]', $search);
        }
        foreach($fournisseurs as $pers) {
            $fournisseurAvatars[] = base64_encode(stream_get_contents($pers->getAvatar()));
        }

        foreach($prestataires as $pers) {
            $prestataireAvatars[] = base64_encode(stream_get_contents($pers->getAvatar()));
        }

        return $this->render('prestataires/prestataire.html.twig', [
            'fournisseurs' => $fournisseurs,
            'prestataires' => $prestataires,
            'fournisseurAvatars' => $fournisseurAvatars,
            'prestataireAvatars' => $prestataireAvatars,
        ]);
    }
}
