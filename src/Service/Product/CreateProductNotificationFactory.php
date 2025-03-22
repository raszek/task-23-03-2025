<?php

namespace App\Service\Product;

use App\Event\CreateProductEvent;
use App\Service\Notification\EmailNotification;
use App\Service\Notification\Notification;
use App\Service\Notification\SlackNotification;
use App\Service\Notification\SmsNotification;
use App\Service\SlackApi\SlackApi;
use App\Service\SlackApi\SlackMessage;
use App\Service\SmsApi\SmsApi;
use App\Service\SmsApi\SmsMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class CreateProductNotificationFactory
{

    public function __construct(
        private MailerInterface $mailer,
        private SmsApi $smsApi,
        private SlackApi $slackApi,
    ) {
    }

    public function create(string $notificationType, CreateProductEvent $event): Notification
    {
        return match ($notificationType) {
            EmailNotification::class => $this->createEmailNotification($event),
            SmsNotification::class => $this->createSmsNotification($event),
            SlackNotification::class => $this->createSlackNotification($event)
        };
    }

    private function createSlackNotification(CreateProductEvent $event): SlackNotification
    {
        $message = new SlackMessage(
            destination: 'someslackid',
            message: $this->getMessage($event),
        );

        return new SlackNotification(
            $this->slackApi,
            $message,
        );
    }

    private function createSmsNotification(CreateProductEvent $event): SmsNotification
    {
        $message = new SmsMessage(
            destinationPhone: '123123123',
            message: $this->getMessage($event),
        );

        return new SmsNotification(
            $this->smsApi,
            $message,
        );
    }

    private function createEmailNotification(CreateProductEvent $event): EmailNotification
    {
        $subject = sprintf('New product %s has been created.', $event->name);

        $email = (new Email())
            ->from('site@example.com')
            ->to('admin@example.com')
            ->subject($subject)
            ->text($this->getMessage($event));

        return new EmailNotification(
            mailer: $this->mailer,
            email: $email,
        );
    }

    private function getMessage(CreateProductEvent $event): string
    {
        return sprintf(
            'Product with name: %s, price: %s, categories: [%s] has been created.',
            $event->name,
            $event->price,
            implode(',', $event->categories)
        );
    }
}
