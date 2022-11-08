<?php

namespace App\Controller;

use App\Entity\TodoDb;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class todoController extends AbstractController
{

    #[Route('/todo')]
    public function number(EntityManagerInterface $entityManager): Response
    {
        //Add a new task
        $task = new TodoDb();
        $task->setTask('This is a sample task...!');

        //store it the db
        $entityManager->persist($task);
        $entityManager->flush();

        //parse data 
        $todoDbRepository = $entityManager->getRepository(TodoDb::class);
        $tasks = $todoDbRepository->findAll();

        //render and pass data to the page
        return $this->render('todoapp.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}