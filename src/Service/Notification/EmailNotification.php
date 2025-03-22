<?php

namespace App\Service\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class EmailNotification implements Notification
{

    public function __construct(
        private MailerInterface $mailer,
        private Email $email
    ) {
    }

    public function send(): void
    {
        $this->mailer->send($this->email);
    }
}
