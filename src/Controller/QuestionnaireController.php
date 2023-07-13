<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Form\QuestionnaireType;
use App\Repository\ConseilRepository;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionnaireController extends AbstractController
{
    #[Route('/questionnaire', name: 'app_questionnaire')]
    public function index(QuestionnaireRepository $questionnaireRepository, UserRepository $userRepository, ReponseRepository $reponseRepository): Response
    {
        $questionnaires = $questionnaireRepository->findAll();
        $images = [];
        $tabQuestionnaireResultat = [];
        if (null != $this->getUser()) {
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            foreach ($questionnaires as $questionnaire) {
                $reponsesOfAQuestionnaire = $user->getReponsesOfAQuestionnaire($questionnaire->getId());
                if (count($reponsesOfAQuestionnaire) > 0) {
                    $tabQuestionnaireResultat[$questionnaire->getId()] = $reponseRepository->getNoteOfReponses($reponsesOfAQuestionnaire);
                }
            }
        }
        $tabQuestionnaireNote = [];
        foreach ($questionnaires as $questionnaire) {
            $tabQuestionnaireNote[$questionnaire->getId()] = $questionnaire->getNoteTotalForEachCategorieQuestion();
            $tabQuestionnaireNote[$questionnaire->getId()]['noteTotal'] = $questionnaire->getNoteTotal(
                isset($user) && ([] != $user->getReponsesOfAQuestionnaire($questionnaire->getId()) && !$questionnaire->checkIfReponsesIsOnAllQuestionnaire($user->getReponsesOfAQuestionnaire($questionnaire->getId())))
            );
            if (!is_null($questionnaire->getImagePresentation())) {
                $images[$questionnaire->getId()] = base64_encode(stream_get_contents($questionnaire->getImagePresentation()));
            }
        }

        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaires,
            'images' => $images,
            'tabQuestionnaireResultat' => $tabQuestionnaireResultat,
            'tabQuestionnaireNote' => $tabQuestionnaireNote,
        ]);
    }

    #[Route('/questionnaire/{id}', name: 'app_questionnaire_id', requirements: ['id' => '\d+'])]
    public function showQuestionnaire(Questionnaire $questionnaire, Request $request, UserRepository $userRepository): Response
    {
        $questions = $questionnaire->getQuestions();
        $nbQuestion = count($questions);
        $nbQuestionNoConnect = count($questionnaire->getQuestions(true));
        $questionRep = [];
        foreach ($questions as $question) {
            $questionRep[$question->getId()] = $question->getReponses();
        }
        $UserRep = [];
        if (!is_null($request->query->get('previousReponses'))) {
            if (null != $this->getUser()) {
                $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
                if (!is_null($user)) {
                    $UserRep = $user->getReponsesOfAQuestionnaire($questionnaire->getId());
                }
            }
        }

        return $this->render('questionnaire/showQuestionnaire.html.twig', [
            'questionnaire' => $questionnaire,
            'questions' => $questions,
            'nbQuestion' => $nbQuestion,
            'nbQuestionNoConnect' => $nbQuestionNoConnect,
            'reponses' => $questionRep,
            'issue' => !is_null($request->query->get('issue')),
            'UserRep' => $UserRep,
            'translationRole' => [
                'ROLE_FOURNISSEUR' => 'Fournisseur',
                'ROLE_PRESTATAIRE' => 'Prestataire',
                'ROLE_VIGNERON' => 'Vigneron',
            ],
        ]);
    }

    #[Route('/questionnaire/{id}/resultat', name: 'app_questionnaire_id_resultat', requirements: ['id' => '\d+'])]
    public function showResultQuestionnaire(ManagerRegistry $doctrine, ReponseRepository $reponseRepository, UserRepository $userRepository, ConseilRepository $conseilRepository, Questionnaire $questionnaire, Request $request): Response
    {
        $Data = true;
        $Request = false;
        $reponses = [];
        if (null != $this->getUser()) {
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        }
        if ($request->request->count() > 0) {
            $Request = true;
            foreach ($request->request as $RepID) {
                $RepID = strip_tags($RepID);
                if (ctype_digit($RepID)) {
                    $reponse = $reponseRepository->findOneBy(['id' => (int) $RepID]);
                    $reponses[] = $reponse;
                }
            }
            $allTheQuestionnaire = $questionnaire->checkIfReponsesMatch($reponses, isset($user) ? $user->getRoles() : null);
            if (is_null($allTheQuestionnaire)) {
                return $this->redirectToRoute('app_questionnaire_id', [
                    'id' => $questionnaire->getId(),
                    'issue' => true,
                ]);
            }
            if (isset($user)) {
                $user->removeReponseOfAQuestionnaire($questionnaire->getId());
                foreach ($reponses as $reponse) {
                    $user->addReponse($reponse);
                    $doctrine->getManager()->persist($reponse);
                }
                $doctrine->getManager()->persist($user);
                $doctrine->getManager()->flush();
            }
        } else {
            if (isset($user)) {
                $reponsesOfAQuestionnaire = $user->getReponsesOfAQuestionnaire($questionnaire->getId());
                if (count($reponsesOfAQuestionnaire) > 0) {
                    $reponses = $reponsesOfAQuestionnaire;
                } else {
                    $Data = false;
                }
            } else {
                return $this->redirectToRoute('app_questionnaire_id', [
                    'id' => $questionnaire->getId(),
                ]);
            }
        }

        list($conseils, $reponsesNote, $isOnAllQuestionnaire, $tabNoteQuestionnaire) =
            $this->conseilsAndNote($questionnaire, $reponses, $Data, $reponseRepository, $conseilRepository);

        return $this->render('questionnaire/showResultQuestionnaire.html.twig', [
            'user' => null,
            'adminView' => false,
            'Data' => $Data,
            'Connect' => isset($user),
            'Request' => $Request,
            'tabNoteQuestionnaire' => $tabNoteQuestionnaire,
            'tabNoteReponses' => $reponsesNote,
            'questionnaire' => $questionnaire,
            'conseils' => $conseils,
            'AllQuestionnaire' => $isOnAllQuestionnaire,
        ]);
    }

    #[Route('/questionnaire/{id}/resultat/user/{idUser}', name: 'app_questionnaire_id_resultat_user', requirements: ['id' => '\d+', 'idUser' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function showResultQuestionnaireOfAUser(int $idUser, ReponseRepository $reponseRepository, UserRepository $userRepository, ConseilRepository $conseilRepository, Questionnaire $questionnaire): Response
    {
        $user = $userRepository->findOneBy(['id' => $idUser]);
        if (!is_null($user)) {
            $reponses = $user->getReponsesOfAQuestionnaire($questionnaire->getId());
            if (count($reponses) > 0) {
                list($conseils, $reponsesNote, $isOnAllQuestionnaire, $tabNoteQuestionnaire) =
                    $this->conseilsAndNote($questionnaire, $reponses, true, $reponseRepository, $conseilRepository);

                return $this->render('questionnaire/showResultQuestionnaire.html.twig', [
                    'user' => $user,
                    'adminView' => true,
                    'Data' => true,
                    'Connect' => false,
                    'Request' => false,
                    'tabNoteQuestionnaire' => $tabNoteQuestionnaire,
                    'tabNoteReponses' => $reponsesNote,
                    'questionnaire' => $questionnaire,
                    'conseils' => $conseils,
                    'AllQuestionnaire' => $isOnAllQuestionnaire,
                ]);
            } else {
                return $this->redirectToRoute('app_profile_show', ['id' => $idUser]);
            }
        } else {
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/questionnaire/{id}/update', name: 'app_questionnaire_update', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function update(ConseilRepository $conseilRepository, ReponseRepository $reponseRepository, QuestionRepository $questionRepository, Questionnaire $questionnaire, Request $request, ManagerRegistry $doctrine): Response
    {
        if (0 == count($questionnaire->getRoleConnecte())) {
            $questionnaire->setRoleConnecte(['ROLE_USER']);
        }
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $questionnaire = $form->getData();
            $questionnaire->controlUpdateQuestionnaire();
            $questionQ = $questionRepository->findBy(['questionnaire' => $questionnaire->getId()]);
            $conseilQ = $conseilRepository->findBy(['questionnaire' => $questionnaire->getId()]);
            foreach ($questionQ as $question) {
                if (!in_array($question, $questionnaire->getQuestions()->getValues())) {
                    foreach ($question->getReponses() as $reponse) {
                        $reponseRepository->remove($reponse);
                    }
                    $questionRepository->remove($question);
                }
            }
            foreach ($conseilQ as $conseil) {
                if (!in_array($conseil, $questionnaire->getConseils()->getValues())) {
                    $conseilRepository->remove($conseil);
                }
            }
            $doctrine->getManager()->persist($questionnaire);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute(
                'app_questionnaire_id', ['id' => $questionnaire->getId()]
            );
        }

        return $this->renderForm('questionnaire/update.html.twig', [
            'questionnaire' => $questionnaire,
            'imagePresentation' => is_null($questionnaire->getImagePresentation()) ? null : base64_encode(stream_get_contents($questionnaire->getImagePresentation())),
            'form' => $form,
        ]);
    }

    #[Route('/questionnaire/create', name: 'app_questionnaire_create', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(QuestionnaireType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $questionnaire = $form->getData();
            $questionnaire->controlUpdateQuestionnaire();
            $doctrine->getManager()->persist($questionnaire);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_questionnaire_id', [
                'id' => $questionnaire->getId(),
            ]);
        }

        return $this->render('questionnaire/create.html.twig', [
            'form' => $form->createView(),
            'questionnaire' => null,
        ]);
    }

    #[Route('/questionnaire/{id}/delete', name: 'app_questionnaire_delete', requirements: ['id' => '\d+'])]
    #[Security("is_granted('ROLE_ADMIN')", message: 'Accès interdit.', statusCode: 404)]
    public function delete(Questionnaire $questionnaire, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->get('delete')->isClicked()) {
            foreach ($questionnaire->getQuestions() as $question) {
                foreach ($question->getReponses() as $reponse) {
                    $doctrine->getManager()->remove($reponse);
                }
                $doctrine->getManager()->remove($question);
            }
            foreach ($questionnaire->getConseils() as $conseil) {
                $doctrine->getManager()->remove($conseil);
            }
            $doctrine->getManager()->remove($questionnaire);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_questionnaire');
        }
        if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('app_questionnaire');
        }

        return $this->renderForm('questionnaire/delete.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form,
        ]);
    }

    public function conseilsAndNote(Questionnaire $questionnaire, array $reponses, bool $Data, ReponseRepository $reponseRepository, ConseilRepository $conseilRepository): array
    {
        $conseils = [];
        $reponsesNote = [];
        $isOnAllQuestionnaire = $questionnaire->checkIfReponsesIsOnAllQuestionnaire($reponses);
        if ($Data) {
            $reponsesNote = $reponseRepository->getNoteOfReponses($reponses);
            $conseils = $conseilRepository->getConseilsOfReponses($questionnaire, $reponsesNote, $isOnAllQuestionnaire);
        }
        $tabNoteQuestionnaire = $questionnaire->getNoteTotalForEachCategorieQuestion();
        $tabNoteQuestionnaire['noteTotal'] = $questionnaire->getNoteTotal(!$isOnAllQuestionnaire);

        return [$conseils, $reponsesNote, $isOnAllQuestionnaire, $tabNoteQuestionnaire];
    }
}
