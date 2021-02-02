<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Task;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="tasks")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()
                 ->getRepository(Task::class)
                 ->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' =>  $tasks
        ]);
    }

    /**
     * @Route("/tasks/create", name="create-task")
     */
    public function create(ValidatorInterface $validator) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTask("Drink More Espresso");
        $task->setCompleted(false);
        $task->setNotes(222);

        $errors = $validator->validate($task);

        if(count($errors) > 0)
        {
            return new Response((string) $errors, 400);
        }
        {
            $entityManager->persist($task);
            $entityManager->flush();
            return new Response("New Task Created!");
        }
    }
}
