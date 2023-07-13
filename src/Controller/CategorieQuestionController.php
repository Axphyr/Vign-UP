<?php

namespace App\Controller;

use App\Entity\CategorieQuestion;
use App\Form\CategorieQuestionType;
use App\Repository\CategorieQuestionRepository;
use App\Repository\QuestionnaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class CategorieQuestionController extends AbstractController
{
    #[Route('/categorieQuestion', name: 'app_categorieQuestion')]
    public function index(CategorieQuestionRepository $categorieQuestionRepository): Response
    {
        $categoriesQuestion = $categorieQuestionRepository->findAll();

        return $this->render('categorie_question/index.html.twig', [
            'categoriesQuestion' => $categoriesQuestion,
        ]);
    }

    #[Route('/categorieQuestion/{id}', name: 'app_categorieQuestion_id', requirements: ['id' => '\d+'])]
    public function showCategorieQuestion(QuestionnaireRepository $questionnaireRepository, CategorieQuestion $categorieQuestion): Response
    {
        $questionnaires = $questionnaireRepository->findAll();
        $questionnaireQuest = [];
        $questionnaireConseil = [];
        foreach ($questionnaires as $questionnaire) {
            $questionnaireQuest[$questionnaire->getId()] =
                $questionnaire->getQuestions(false, $categorieQuestion);
            $questionnaireConseil[$questionnaire->getId()] =
                $questionnaire->getConseils($categorieQuestion);
        }

        dump($questionnaireQuest);
        return $this->render('categorie_question/show.html.twig', [
            'categorieQuestion' => $categorieQuestion,
            'questionnaires' => $questionnaires,
            'questionnaireQuest' => $questionnaireQuest,
            'questionnaireConseil' => $questionnaireConseil,
        ]);
    }

    #[Route('/categorieQuestion/{id}/update', name: 'app_categorieQuestion_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, CategorieQuestion $categorieQuestion, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(CategorieQuestionType::class, $categorieQuestion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorieQuestion = $form->getData();
            $doctrine->getManager()->persist($categorieQuestion);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute(
                'app_categorieQuestion',
            );
        }

        return $this->renderForm('categorie_question/update.html.twig', [
            'categorieQuestion' => $categorieQuestion,
            'form' => $form,
        ]);
    }

    #[Route('/categorieQuestion/create', name: 'app_categorieQuestion_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(CategorieQuestionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorieQuestion = $form->getData();
            $doctrine->getManager()->persist($categorieQuestion);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute(
                'app_categorieQuestion',
            );
        }

        return $this->render('categorie_question/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categorieQuestion/{id}/delete', name: 'app_categorieQuestion_delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request, CategorieQuestion $categorieQuestion, ManagerRegistry $doctrine): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->get('delete')->isClicked()) {
            foreach ($categorieQuestion->getConseils() as $conseil) {
                $conseil->setCategorieQuestion(null);
            }
            foreach ($categorieQuestion->getQuestion() as $question) {
                $question->setCategorieQuestion(null);
            }
            $doctrine->getManager()->remove($categorieQuestion);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_categorieQuestion');
        }
        if ($form->isSubmitted() && $form->get('cancel')->isClicked()) {
            return $this->redirectToRoute('app_categorieQuestion');
        }

        return $this->renderForm('categorie_question/delete.html.twig', [
            'categorieQuestion' => $categorieQuestion,
            'form' => $form,
        ]);
    }
}
