<?php
namespace App\Controller;

use App\Entity\Menus;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Orders;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generator;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->generator = $generator;
    }

    #[Route('/order/create-session-stripe/{reference}', name: 'stripe_payment')]
    public function stripeCheckout($reference, Request $request): RedirectResponse
    {
        $menusStripe = [];

        $order = $this->em->getRepository(Orders::class)->findOneBy(['reference' => $reference]);

        if (!$order) {
            return $this->redirectToRoute('app_cart');
        }

        foreach ($order->getRecapDetails()->getValues() as $menus) {
            $menusData = $this->em->getRepository(Menus::class)->findOneBy(['name' => $menus->getMenus()]);
            $menusStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $menusData->getPrice(),
                    'product_data' => [
                        'name' => $menus->getMenus(),
                    ]
                ],
                'quantity' => $menus->getQuantity(),
            ];
        }

        \Stripe\Stripe::setApiKey('sk_test_51PfiiKRs76GVw4DFJKKWi6Cs04ZcDsoRAQbX0QvLsqQBbYfAnOwJIkI7Xl9991c3shVjiJU6Qv7StHMSMxTf1qO400WBriHEMn');
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    $menusStripe
                ]
            ],
            'mode' => 'payment',
            'success_url' => $this->generator->generate('payment_success', [
                'reference' => $order->getReference()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generator->generate('payment_error', [
                'reference' => $order->getReference()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $order->setStripeSessionId($checkout_session->id);
        $this->em->flush();

        return $this->redirect($checkout_session->url);
    }

    #[Route('/order/success/{reference}', name: 'payment_success')]
    public function stripeSuccess($reference, CartService $service, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $order = $this->em->getRepository(Orders::class)->findOneBy(['reference' => $reference]);
        $userEmail = $user->getEmail();
        if (!$order) {
            $this->addFlash('error', 'Commande non trouvée.');
            return $this->redirectToRoute('app_cart');
        }

        $emailContent = '<p>Votre commande a bien été prise en compte et sera prête d\'ici 15 min.</p>';
        $emailContent .= '<p>Détail de votre commande:</p>';
        $emailContent .= '<ul>';
        foreach ($order->getRecapDetails()->getValues() as $menus) {
            $menusData = $this->em->getRepository(Menus::class)->findOneBy(['name' => $menus->getMenus()]);
            $emailContent .= '<li>';
            $emailContent .= 'Menu: ' . $menus->getMenus() . '<br>';
            $emailContent .= 'Quantité: ' . $menus->getQuantity() . '<br>';
            $emailContent .= 'Prix unitaire: ' . number_format($menusData->getPrice() / 100, 2) . ' €';
            $emailContent .= '</li>';
        }
        $emailContent .= '</ul>';

        $email = (new Email())
            ->from('no-reply@yourdomain.com')
            ->to($userEmail)
            ->subject('Confirmation de votre commande')
            ->html($emailContent);

        $mailer->send($email);

        // Vider le panier après succès du paiement
        $service->removeCartAll();

        return $this->render('orders/success.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/order/error/{reference}', name: 'payment_error')]
    public function stripeError($reference, CartService $service): Response
    {
        return $this->render('orders/error.html.twig');
    }
}


