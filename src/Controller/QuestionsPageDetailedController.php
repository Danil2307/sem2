<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Form\PublicAnswerType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsPageDetailedController extends AbstractController
{
    #[Route('/questions/{id}', name: 'app_questions_page_detailed')]
    public function index($id, ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $doctrine->getRepository(Questions::class);
        $question = $repository->find($id);

        if(!$question){
            return $this->redirect('/');
        }

        if($question->isFlag() == false){
            return $this->redirect('/');
        }

        //Добавление комментария
        $answer = new Answers();
        $user = $this->getUser();
        $formAnswer = $this->createForm(PublicAnswerType::class, $answer);
        $formAnswer->handleRequest($request);

        if ($formAnswer->isSubmitted() && $formAnswer->isValid())
        {
            $date = new \DateTime('@'.strtotime('now + 3 hours'));
            $answer->setDate($date);
            $answer->setUser($user);
            $answer->setQuestion($question);

            $answer->setFlag(false);

            $entityManager->persist($answer);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirect('/');

        }

        return $this->render('questions_page_detailed/index.html.twig', [
            'question' => $question,
            'formAnswer' => $formAnswer->createView(),
        ]);
    }
}
