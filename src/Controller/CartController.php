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

    #[Route('/cart/order', name: 'app_cart_order')]
    public function createOrder(CartService $cartService, EntityManagerInterface $manager): RedirectResponse
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez d\'abord vous connecter');
            return $this->redirectToRoute('app_login');
        }

        $order = new Orders();
        $order->setUserId($user);
        $order->setStatus('pending'); // Set the initial status of the order
        $order->setRequestedDate(new \DateTime());

        $manager->persist($order);
        $manager->flush();

        // Add products from cart to order
        $cartItems = $cartService->getTotal();
        foreach ($cartItems as $item) {
            $orderProduct = new OrdersProducts();
            $orderProduct->setOrderId($order);
            $orderProduct->setProductId($item['menus']); // Assuming 'menus' is the product entity
            $orderProduct->setQuantity($item['quantity']); // Add quantity if needed

            $manager->persist($orderProduct);
        }

        $manager->flush();

        return $this->redirectToRoute('stripe_payment', ['id' => $order->getId()]);
    }
}