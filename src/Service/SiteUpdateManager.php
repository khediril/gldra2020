<?php 
// src/Updates/SiteUpdateManager.php
namespace App\Service;

use App\Service\MessageGenerator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SiteUpdateManager
{
    private $messageGenerator;
    private $mailer;
    private $adminEmail;

    public function __construct(MessageGenerator $messageGenerator,  \Swift_Mailer  $mailer, $admMail)
    {
        $this->messageGenerator = $messageGenerator;
        $this->mailer = $mailer;
        $this->adminEmail = $admMail;
    }

    public function notifyOfSiteUpdate()
    {
        $happyMessage = $this->messageGenerator->getHappyMessage();

        $email = (new \Swift_Message())
            ->setFrom($this->adminEmail)
            ->setTo('manager@example.com')
            ->setBody('Someone just updated the site. We told them: '.$happyMessage);

        $this->mailer->send($email);

        // ...
    }
}
