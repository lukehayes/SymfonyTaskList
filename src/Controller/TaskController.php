<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="tasks")
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * @Route("/tasks/create", name="create-task")
     */
    public function create() : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $task = new Task();
        $task->setTask("Refactor code on project");
        $task->setCompleted(false);
        $task->setNote("This is important!");

        $entityManager->persist($task);
        $entityManager->flush();

        return new Response("New Task Created!");
    }
}
