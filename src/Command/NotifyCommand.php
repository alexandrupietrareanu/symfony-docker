<?php

namespace App\Command;

use App\Service\Notification\Interfaces\NotifierInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

#[AsCommand(
    name: 'app:notify',
    description: 'Notify users',
)]
class NotifyCommand extends Command
{
    /**
     * @param iterable<NotifierInterface> $allNotifiers
     */
    public function __construct(
        #[AutowireLocator('app.notifier')]
        private readonly ContainerInterface $handlers,
        #[AutowireIterator('app.notifier')]
        private readonly iterable $allNotifiers,
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: 'Message content')]
        string $message,
        #[Option(description: 'Message channel')]
        ?string $channel = null,
    ): int {
        if (null === $channel) {
            foreach ($this->allNotifiers as $notifier) {
                $notifier->notify($message);
            }
            $io->success('Broadcast to all channels');

            return Command::SUCCESS;
        }

        if (!$this->handlers->has($channel)) {
            $io->error(sprintf('Invalid channel "%s".', $channel));

            return Command::FAILURE;
        }

        $service = $this->handlers->get($channel);

        if (!$service instanceof NotifierInterface) {
            throw new \Exception('Invalid service');
        }

        $service->notify($message);

        $io->success(sprintf('Sent via %s.', $channel));

        return Command::SUCCESS;
    }
}
