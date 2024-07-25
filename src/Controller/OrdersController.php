<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\RecapDetails;
use App\Form\OrdersType;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/lecabanon', name: 'app_cabanon')]
    public function index(): Response
    {
        return $this->render('orders/cabanon.html.twig', [
            'controller_name' => 'OrdersController',
        ]);
    }


    #[Route('/order/create', name: 'app_order_create')]
    public function create(CartService $cartService): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez d\'abord vous connecter');
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(OrdersType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('orders/order.html.twig', [
            'form' => $form->createView(),
            'recapCart' => $cartService->getTotal()
        ]);
    }




    #[Route('/order/verify', name: 'app_order_verify', methods: ['POST'])]
    public function prepareOrder(CartService $cartService, Request $request): Response
    {
        $form = $this->createForm(OrdersType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = new \DateTime('now');
            $order = new Orders();
            $order->setUserId($this->getUser());
            $reference = $datetime->format('dmY') . '-' . uniqid();
            $order->setReference($reference);
            $order->setUserId($this->getUser());
            $order->setCreatedAt($datetime);
            $order->setStatus(0);

            $this->em->persist($order);


            foreach ($cartService->getTotal() as $menus) {
                $recapDetails = new RecapDetails();
                $recapDetails->setOrderProduct($order);
                $recapDetails->setQuantity($menus['quantity']);
                $recapDetails->setPrice($menus['menus']->getPrice());
                $recapDetails->setMenus($menus['menus']->getName());
                $recapDetails->setTotalRecap($menus['menus']->getPrice() * $menus['quantity']);

                $this->em->persist($recapDetails);
            }

            $this->em->flush();
        }
        return $this->render('orders/recap.html.twig', [
            'recapCart' => $cartService->getTotal(),
            'reference' => $order->getReference(),
        ]);
    }
}

