<?php

namespace App\Controller;

use App\Entity\Menus;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenusController extends AbstractController
{
    #[Route('/menus', name: 'app_menus')]
    public function index(EntityManagerInterface $manager): Response
    {
        $menus = $manager->getRepository(Menus::class)
            ->findAll();

        if (!$menus) {
            $this->addFlash('error', 'Désolé, il n\'y a aucun menus de disponible');
        }
        $products = $manager->getRepository(Products::class)->findAll();
        if (!$products) {
            $this->addFlash('error', 'Désolé, il n\'y a aucun produit de disponible');
        }

        return $this->render('menus/menus.html.twig', [
            'menus' => $menus,
            'products' => $products,
        ]);
    }
}
