<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\OrdersType;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
   
    #[Route('/cart', name: 'app_cart')]
    public function index(EntityManagerInterface $manager, CartService $cartService, Request $request): Response
    {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez d\'abord vous connecter');
            return $this->redirectToRoute('app_login');
        }

        $orders = new Orders();
        // récupération de l'id utilisateur
        $user = $this->getUser();
        $orders->setUserId($user);


        $form = $this->createForm(OrdersType::class, $orders);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          

            $orders->setUserId($user);
            $manager->persist($orders);
            $manager->flush();
        }
        return $this->render('cart/cart.html.twig', [
            'cart' => $cartService->getTotal()
        ]);

        
    }
   
   
    #[Route('/cart/add/{id<\d+>}', name: 'app_cart_add')]
    public function addToCart(CartService $cartService, int $id): Response
    {
        $cartService->addToCart($id);

        return $this->redirectToRoute('app_cart');
    }


    #[Route('/cart/remove/{id<\d+>}', name: 'app_cart_removeone')]
    public function removeToCart(CartService $cartService, int $id): Response
    {
        $cartService->removeToCart($id);

        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/removeAll', name: 'app_cart_remove')]
    public function removeCart(CartService $cartService): Response
    {
        $cartService->removeCartAll();

        return $this->redirectToRoute('app_menus');
    }

    #[Route('/cart/decrease/{id<\d+>}', name: 'app_cart_decrease')]
    public function decrease(CartService $cartService, $id): RedirectResponse
    {
        $cartService->decrease($id);

        return $this->redirectToRoute('app_cart');
    }
}
