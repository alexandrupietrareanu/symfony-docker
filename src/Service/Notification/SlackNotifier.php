<?php

namespace App\Service\Notification;

use App\Service\Notification\Interfaces\NotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'slack')]
class SlackNotifier implements NotifierInterface
{
    public function notify(string $message): void
    {
        // TODO: Implement notify() method.
        echo 'slack';
    }
}
