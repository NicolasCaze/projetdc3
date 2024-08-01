<?php
// src/Controller/DataUserController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DataUserController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/information-compte', name: 'app_data_user')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez d\'abord vous connecter');
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($plainPassword = $form->get('plainPassword')->getData()) {
                $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
            }

            $this->em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Vos informations ont été mises à jour.',
                ]);
            }

            $this->addFlash('success', 'Vos informations ont été mises à jour.');
            $email = (new Email())
                ->from('no-reply@adapei.com')
                ->to('user@example.com')
                ->subject('Modifications enregistrés')
                ->text('Vos informations ont bien été enregistrées')
                ->html('<p>Vos informations ont bien été enregistrées</p>');

            $mailer->send($email);
            return $this->redirectToRoute('app_data_user');

        }

        if ($form->isSubmitted() && !$form->isValid() && $request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Il y a eu un problème avec la soumission du formulaire.',
            ]);
        }


        return $this->render('data_user/compte.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);


    }
}
