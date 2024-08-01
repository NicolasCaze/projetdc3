<?php
// src/Command/SendTestEmailCommand.php

namespace App\Command;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SendTestEmailCommand extends Command
{
    protected static $defaultName = 'app:send-test-email';

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Send a test email.')
            ->addArgument('to', InputArgument::REQUIRED, 'Recipient email address');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $recipient = $input->getArgument('to');

        $email = (new Email())
            ->from('no-reply@yourdomain.com')
            ->to($recipient)
            ->subject('Test Email')
            ->text('This is a test email.');

        try {
            $this->mailer->send($email);
            $output->writeln('Test email sent successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Failed to send test email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
