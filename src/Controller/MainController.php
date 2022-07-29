<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class MainController extends AbstractController
{
    
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        $session = new Session();
        $user = $session->get('user', 0);
        $username = $session->get('username', 'аноним');
        
        include "templates/home.php";
        return new Response("");
        
    }
    
    #[Route('/new_post', name: 'new_post')]
    public function new_post(): Response
    {
        $session = new Session();
        $user = $session->get('user', 0);
        $username = $session->get('username', 'аноним');
        
        include "templates/new_post.php";
        return new Response("");
        
    }
    
    #[Route('/new_user', name: 'new_user')]
    public function new_user(): Response
    {
        $session = new Session();
        $user = $session->get('user', 0);
        $username = $session->get('username', 'аноним');
        
        include "templates/new_user.php";
        return new Response("");
        
    }
}
