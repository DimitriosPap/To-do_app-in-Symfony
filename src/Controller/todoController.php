<?php

namespace App\Controller;

use App\Entity\Todoit;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class todoController extends AbstractController
{

    #[Route('/todo', name:'list')]
    public function number(PersistenceManagerRegistry $doctrine)
    {

        //parse data FIRST entry first
        $tasks = $doctrine->getRepository(Todoit::class)->findAll();
        //parse data LAST entry first
        //$tasks = $doctrine->getRepository(Todoit::class)->findBy([],['id'=>'DESC']);

        //render and pass data to the page
        return $this->render('todoapp.html.twig', [
            'tasks' => $tasks
        ]);
    }

    #[Route('/create', name:'create_task', methods:'POST')]
    public function create(Request $request, PersistenceManagerRegistry $doctrine)
    {
        $task = trim($request->request->get('task'));
        if (empty($task))
        return $this->redirectToRoute('list');

        //Add a new task
        $todo = new Todoit();
        $todo->setTask($task);

        $entityManager = $doctrine->getManager();

        //store it the db
        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }

    #[Route('/delete/{id}', name:'delete_task')]
    public function delete(Todoit $id, PersistenceManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($id);
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }
}