<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
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
        $post = $_POST['post'];
        $text = $_POST['text'];
        $author = $_POST['author'];
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
    
    #[Route('/find_comment_by_post', name: 'find_comment_by_post')]
    public function find_all(ManagerRegistry $doctrine): JsonResponse
    {
        $post = $_POST['post'];
        
        $repository = $doctrine->getRepository(Comment::class);
        $comments = $repository->findBy(
            ['post' => $post],
        );
        
        $repository = $doctrine->getRepository(User::class);
        $commentList = [];
        foreach($comments as $value){
            $author = $repository->find($value->getAuthor());
            $authorObj = ['id' => $author->getId(), 'name' => $author->getName()];
            $commentList[] = [
                'id' => $value->getId(), 
                'text' => $value->getText(), 
                'author' => $authorObj, 
                'created_at' => $value->getCreatedAt()
            ];
        }
        
        return $this->json($commentList);
    }
}
