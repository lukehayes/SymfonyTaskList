<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Task;
use App\Form\TaskType;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="tasks")
     */
    public function index() : Response
    {
        $tasks = $this->getDoctrine()
                 ->getRepository(Task::class)
                 ->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' =>  $tasks
        ]);
    }

    /**
     * @Route("/tasks/update/{id}", name="update-task")
     */
    public function update(int $id, Request $request) : Response
    {
        $task = $this->getDoctrine()
                 ->getRepository(Task::class)
                 ->find($id);

        if(!$task)
            throw $this->createNotFoundException("The task with ID {$id} could not be found.");

        $form = $this->createForm(TaskType::class, $task);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->manager()->persist($task);
            $this->manager()->flush();
            $this->addFlash('notice', 'The task has been updated.');
            return $this->redirectToRoute("tasks");
        }

        return $this->render('task/update.html.twig', [
            'task' =>  $task,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tasks/remove/{id}", name="destroy-task")
     */
    public function destroy(int $id) : Response
    {
        $task = $this->getDoctrine()
                 ->getRepository(Task::class)
                 ->find($id);

        if(!$task)
            throw $this->createNotFoundException("The task with ID {$id} could not be found.");

        $this->manager()->remove($task);
        $this->manager()->flush();

        $this->addFlash('notice', 'The task has been deleted.');

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route("/tasks/show/{id}", name="show-task")
     */
    public function show(int $id) : Response
    {
        $task = $this->getDoctrine()
                 ->getRepository(Task::class)
                 ->find($id);

        if(!$task)
            throw $this->createNotFoundException("The task with ID {$id} could not be found.");

        return $this->render('task/show.html.twig', [
            'task' =>  $task
        ]);
    }

    /**
     * @Route("/tasks/new", name="new-task")
     */
    public function new(Request $request) : Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->manager()->persist($task);
            $this->manager()->flush();
            return $this->redirectToRoute("tasks");
        }

        return $this->render('task/new.html.twig', [
            'form' =>  $form->createView()
        ]);
    }
    /**
     * @Route("/tasks/create", name="create-task")
     */
    public function create(ValidatorInterface $validator) : Response
    {
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
            $this->manager()->persist($task);
            $this->manager()->flush();
            return new Response("New Task Created!");
        }
    }

    private function manager()
    {
        return $this->getDoctrine()->getManager();
    }
}
