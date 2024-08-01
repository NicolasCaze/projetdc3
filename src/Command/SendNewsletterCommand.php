<?php

namespace App\Command;

use App\Repository\NewsletterSubscriberRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class SendNewsletterCommand extends Command
{
    protected static $defaultName = 'app:send-newsletter';

    private $subscriberRepository;
    private $mailer;
    private $twig;

    public function __construct(NewsletterSubscriberRepository $subscriberRepository, MailerInterface $mailer, Environment $twig)
    {
        parent::__construct();

        $this->subscriberRepository = $subscriberRepository;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    protected function configure()
    {
        $this
            ->setDescription('Send the newsletter to all subscribers.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $subscribers = $this->subscriberRepository->findAll();

        foreach ($subscribers as $subscriber) {
            $email = (new Email())
                ->from('no_reply@example.com')
                ->to($subscriber->getEmail())
                ->subject('Newsletter')
                ->html($this->twig->render('emails/newsletter.html.twig'));

            $this->mailer->send($email);
        }

        $output->writeln('Newsletter sent to all subscribers.');

        return Command::SUCCESS;
    }
}
