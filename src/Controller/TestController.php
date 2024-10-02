<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{


    #[Route('/', name: 'app_index')]
    public function index(Session $session): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'msg'=>$session->get('msg', 'Rien en session'),
        ]);
    }

    #[Route("/msg/{message}/{type}")]
    public function showMessage(string $message, string $type = 'primary', Request $request)
    {
        $session = $request -> getSession();
        $session->set('msg', $message);
        return $this->render('test/msg.html.twig', ['msg' => $message, 'type' => $type]);
    }

    #[Route('/post', methods: ['post', 'get'])]
    public function testPost()
    {
        return new Response("Post uniquement");
    }
}
