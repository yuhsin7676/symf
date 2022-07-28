<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): JsonResponse
    {
        $message = $_GET['message'];
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CommentController.php',
            'your message' => $message,
        ]);
    }
    
    #[Route('/create_comment', name: 'create_comment')]
    public function create(ManagerRegistry $doctrine): JsonResponse
    {
        $post = $_GET['post'];
        $text = $_GET['text'];
        $author = $_GET['author'];
        $date = date_create_immutable("now", null);
        
        $comment = new Comment();
        $comment->setAuthor($author)
                ->setPost($post)
                ->setText($text)
                ->setCreatedAt($date);
        
        $entityManager = $doctrine->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
        
        $str = $date->format('Y-m-d');
        return $this->json([
            'message' => 'Comment for "'.$post.'" has been created at "'.$str.'"',
            'id' => $comment->getId(),
        ]);
    }
}
