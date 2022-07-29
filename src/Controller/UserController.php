<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
    
    #[Route('/create_user', name: 'create_user')]
    public function create(ManagerRegistry $doctrine): JsonResponse
    {
        $name = $_GET['name'];
        $date = date_create_immutable("now", null);
        
        $user = new User();
        $user->setName($name)
                ->setCreatedAt($date);
        
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $str = $date->format('Y-m-d');
        return $this->json([
            'message' => 'User "'.$name.'" has been created at "'.$str.'"',
            'id' => $user->getId(),
        ]);
    }
    
    #[Route('/find_all_user', name: 'find_all_user')]
    public function find_all(ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();
        
        $userList = [];
        foreach($users as $value){
            $userList[] = [
                'id' => $value->getId(), 
                'name' => $value->getName(), 
                'created_at' => $value->getCreatedAt()
            ];
        }
        
        return $this->json($userList);
    }
    
}
