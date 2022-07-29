<?php

namespace App\Controller;

use Faker;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FakerController extends AbstractController
{
    
    #[Route('/faker', name: 'faker')]
    public function home(ManagerRegistry $doctrine): Response
    {
        
        $this->createUsers($doctrine);
        $this->createPosts($doctrine);
        $this->createComments($doctrine);
        
        return new Response("");
        
    }
    
    private function createUsers(ManagerRegistry $doctrine){
        
        $faker = Faker\Factory::create();
        
        for($i = 0; $i < 50; $i++){
            $name = $faker->name();
            $date = date_create_immutable("now", null);

            $user = new User();
            $user->setName($name)
                    ->setCreatedAt($date);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        
    }
    
    private function createPosts(ManagerRegistry $doctrine){
        
        $faker = Faker\Factory::create();
        
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();
        
        $userList = [];
        foreach($users as $value){
            $userList[] = $value->getId();
        }
        
        for($i = 0; $i < 10; $i++){
            $title = $faker->word();
            $preview = $faker->text(100);
            $text = $faker->text(1000);
            $author = $faker->randomElement($userList);
            $date = date_create_immutable("now", null);

            $post = new Post();
            $post->setTitle($title)
                    ->setPreview($preview)
                    ->setText($text)
                    ->setAuthor($author)
                    ->setCreatedAt($date);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        }
        
    }
    
    private function createComments(ManagerRegistry $doctrine){
        
        $faker = Faker\Factory::create();
        
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();
        $userList = [];
        foreach($users as $value){
            $userList[] = $value->getId();
        }
        
        $repository = $doctrine->getRepository(Post::class);
        $posts = $repository->findAll();
        $postList = [];
        foreach($posts as $value){
            $postList[] = $value->getId();
        }
        
        for($i = 0; $i < 300; $i++){
            $post = $faker->randomElement($postList);
            $text = $faker->text(200);
            $author = $faker->randomElement($userList);
            $date = date_create_immutable("now", null);

            $comment = new Comment();
            $comment->setAuthor($author)
                    ->setPost($post)
                    ->setText($text)
                    ->setCreatedAt($date);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        
    }
    
}
