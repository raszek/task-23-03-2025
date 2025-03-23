<?php

namespace App\Tests\EventListener;

use App\Event\CreateProductEvent;
use App\EventListener\CreateProductEventListener;
use App\Service\Notification\NotificationList;
use App\Service\Product\CreateProductNotificationFactory;
use App\Service\SlackApi\SlackApi;
use App\Service\SlackApi\SlackMessage;
use App\Service\SmsApi\SmsApi;
use App\Service\SmsApi\SmsMessage;
use App\Tests\KernelTestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

class CreateProductEventListenerTest extends KernelTestCase
{

    /** @test */
    public function it_sends_notifications_using_different_methods()
    {
        $mailerMock = new class implements MailerInterface {

            public bool $isEmailSent = false;

            public function send(RawMessage $message, ?Envelope $envelope = null): void
            {
                $this->isEmailSent = true;
            }
        };

        $smsApiMock = new class implements SmsApi {

            public bool $isSmsSent = false;

            public function send(SmsMessage $message): void
            {
                $this->isSmsSent = true;
            }
        };

        $slackApiMock = new class extends SlackApi {

            public bool $isMessageSent = false;

            public function send(SlackMessage $slackMessage): void
            {
                $this->isMessageSent = true;
            }

        };

        $factory = new CreateProductNotificationFactory(
            mailer: $mailerMock,
            smsApi: $smsApiMock,
            slackApi: $slackApiMock
        );

        $createProductEventListener = new CreateProductEventListener(
            logger: $this->createMock(LoggerInterface::class),
            notificationList: new NotificationList(),
            factory: $factory,
        );

        $createProductEventListener->sendMessages(new CreateProductEvent(
            id: 5,
            name: 'Test Product',
            price: '12.12',
            categories: ['tools', 'house']
        ));

        $this->assertTrue($mailerMock->isEmailSent);
        $this->assertTrue($smsApiMock->isSmsSent);
        $this->assertTrue($slackApiMock->isMessageSent);
    }
}
