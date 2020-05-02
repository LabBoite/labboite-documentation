<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Theme;
use App\Entity\Tool;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/new/{name}", name="question_new", methods={"GET","POST"})
     */
    public function new(Request $request, Tool $tool): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setTool($tool);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index', ['name' => $tool->getName()]);
        }

        return $this->render('question/new.html.twig', [
            'tool' => $tool,
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }
   
    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index', ['name' => $question->getTool()->getName()]);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index');
    }

    /**
     * @Route("/{name}", name="question_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository, Tool $tool): Response
    {
        return $this->render('question/index.html.twig', [
            'tool' => $tool,
            'questions' => $questionRepository->findBy(['tool' => $tool]),
        ]);
    }
}
