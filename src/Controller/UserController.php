<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    
    #[Route('/create_user', name: 'create_user')]
    public function create(ManagerRegistry $doctrine): JsonResponse
    {
        $name = $_POST['name'];
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
    
    #[Route('/choose_user', name: 'choose_user')]
    public function choose_user(ManagerRegistry $doctrine): Response
    {
        $user = $_POST['user'];
        $username = $_POST['username'];
        $session = new Session();
        $session->set('user', $user);
        $session->set('username', $username);
        return new Response("");
    }
    
}
