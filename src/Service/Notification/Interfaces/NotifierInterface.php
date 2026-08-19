<?php

namespace App\Service\Notification\Interfaces;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.notifier')]
interface NotifierInterface
{
    public function notify(string $message): void;
}
