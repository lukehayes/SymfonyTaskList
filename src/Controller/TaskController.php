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
    public function update(int $id) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $task = $this->getDoctrine()
                 ->getRepository(Task::class)
                 ->find($id);

        if(!$task)
            throw $this->createNotFoundException("The task with ID {$id} could not be found.");

        $task->setNotes("");
        $entityManager->flush();

        $this->addFlash('notice', 'The task has been updated.');
        return $this->redirectToRoute('tasks');
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

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('notes', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Add a new task'])
            ->getForm();


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            dump($form->getData());
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
