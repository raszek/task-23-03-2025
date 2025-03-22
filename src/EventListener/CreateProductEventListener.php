<?php

namespace App\EventListener;

use App\Event\CreateProductEvent;
use App\Service\Notification\NotificationList;
use App\Service\Product\CreateProductNotificationFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

readonly class CreateProductEventListener
{

    public function __construct(
        private LoggerInterface $logger,
        private NotificationList $notificationList,
        private CreateProductNotificationFactory $factory
    ) {
    }


    #[AsMessageHandler]
    public function log(CreateProductEvent $createProductEvent): void
    {
        $message = sprintf(
            'Created product with id: %d, name: %s, price: %s, categories: [%s]',
            $createProductEvent->id,
            $createProductEvent->name,
            $createProductEvent->price,
            implode(',', $createProductEvent->categories)
        );

        $this->logger->info($message);
    }

    #[AsMessageHandler]
    public function sendMessages(CreateProductEvent $createProductEvent): void
    {
        $notifications = $this->notificationList->get();

        foreach ($notifications as $notification) {
            $notification = $this->factory->create($notification, $createProductEvent);

            $notification->send();
        }
    }

}
