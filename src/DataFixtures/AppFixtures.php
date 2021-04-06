<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadCategories($manager);
        $this->loadTasks($manager);

    }

    /**
     * Quick and dirty way to load the database with categories.
     */
    private function loadCategories(ObjectManager $manager)
    {
        $categories = ['Work', 'App Development', 'Lifestyle', 'Learning', 'Other'];

        foreach($categories as $cat)
        {
            $category = new Category();
            $category->setName($cat);
            $manager->persist($category);
        }

        $manager->flush();
    }

    private function loadTasks(ObjectManager $manager)
    {
        $categories = ['Work', 'App Development', 'Lifestyle', 'Learning', 'Other'];

        for($i = 0; $i <= 15; $i++)
        {
            $task = new Task();
            $task->setTask("A simple task.");
            $task->setNotes("An example note can be here.");

            $category = new Category();
            $randCategory = $categories[array_rand($categories)];
            $category->setName($randCategory);

            $task->setCategory($category);

            $manager->persist($task);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
