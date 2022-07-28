<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PostController.php',
        ]);
    }
    
    #[Route('/create_post', name: 'create_post')]
    public function create(ManagerRegistry $doctrine): JsonResponse
    {
        $title = $_GET['title'];
        $preview = $_GET['preview'];
        $text = $_GET['text'];
        $author = $_GET['author'];
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
        
        $str = $date->format('Y-m-d');
        return $this->json([
            'message' => 'Post "'.$title.'" has been created at "'.$str.'"',
            'id' => $post->getId(),
        ]);
    }
}
