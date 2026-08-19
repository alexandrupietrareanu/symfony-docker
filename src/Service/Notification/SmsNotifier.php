<?php

namespace App\Service\Notification;

use App\Service\Notification\Interfaces\NotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'sms')]
class SmsNotifier implements NotifierInterface
{
    public function notify(string $message): void
    {
        // TODO: Implement notify() method.
        echo 'sms';
    }
}
