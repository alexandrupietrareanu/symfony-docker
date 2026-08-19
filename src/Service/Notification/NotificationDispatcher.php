<?php

namespace App\Service\Notification;

use App\Service\Notification\Interfaces\NotifierInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class NotificationDispatcher
{
    /**
     * @param iterable<NotifierInterface> $notifiers
     */
    public function __construct(
        #[AutowireIterator('app.notifier')]
        private readonly iterable $notifiers,
        #[AutowireLocator('app.notifier')]
        private readonly ContainerInterface $locator,
    ) {
    }

    public function notify(string $message): void
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->notify($message);
        }
    }

    public function resolveChannel(string $channel): ?NotifierInterface
    {
        /**
         * @var NotifierInterface|null $notifier
         */
        $notifier = $this->locator->has($channel) ? $this->locator->get($channel) : null;

        return $notifier;
    }
}
