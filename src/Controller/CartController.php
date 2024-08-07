<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez d\'abord vous connecter');
            return $this->redirectToRoute('app_login');
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
    public function decrease(CartService $cartService, int $id): RedirectResponse
    {
        $cartService->decrease($id);
        return $this->redirectToRoute('app_cart');
    }

}