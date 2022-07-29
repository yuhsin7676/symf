<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
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
        $title = $_POST['title'];
        $preview = $_POST['preview'];
        $text = $_POST['text'];
        $author = $_POST['author'];
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
    
    #[Route('/find_all_post', name: 'find_all_post')]
    public function find_all(ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $doctrine->getRepository(Post::class);
        $posts = $repository->findAll();
        
        $repository = $doctrine->getRepository(User::class);
        $postList = [];
        foreach($posts as $value){
            $author = $repository->find($value->getAuthor());
            $authorObj = ['id' => $author->getId(), 'name' => $author->getName()];
            $postList[] = ['id' => $value->getId(), 'title' => $value->getTitle(), 'preview' => $value->getPreview(), 'author' => $authorObj, 'created_at' => $value->getCreatedAt()];
        }
        
        return $this->json($postList);
    }
    
    #[Route('/find_post', name: 'find_post')]
    public function find(ManagerRegistry $doctrine): JsonResponse
    {
        $id = $_POST['id'];
        $repository = $doctrine->getRepository(Post::class);
        $post = $repository->find($id);
        
        $repository = $doctrine->getRepository(User::class);
        $author = $repository->find($post->getAuthor());
        $authorObj = ['id' => $author->getId(), 'name' => $author->getName()];
          
        $post = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'preview' => $post->getPreview(),
            'author' => $authorObj,
            'text' => $post->getText(),
            'created_at' => $post->getCreatedAt()
        ];
        
        return $this->json($post);
    }
}
