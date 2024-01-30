<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    #[Route('/lecabanon', name: 'app_cabanon')]
    public function index(): Response
    {
        return $this->render('orders/cabanon.html.twig', [
            'controller_name' => 'OrdersController',
        ]);
    }
}
