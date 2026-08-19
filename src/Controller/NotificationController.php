<?php

namespace App\Controller;

use App\Dto\NotifyRequest;
use App\Service\Notification\NotificationDispatcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/notify', name: 'api_notify', methods: ['POST'])]
#[IsGranted('ROLE_API_USER')]
final class NotificationController extends AbstractController
{
    public function __construct(
        private readonly NotificationDispatcher $notificationDispatcher,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] NotifyRequest $notifyRequest,
    ): JsonResponse {
        if (null === $notifyRequest->channel) {
            $this->notificationDispatcher->notify($notifyRequest->message);

            return new JsonResponse('ok', Response::HTTP_OK);
        }

        $notifier = $this->notificationDispatcher->resolveChannel($notifyRequest->channel);

        if (null === $notifier) {
            return new JsonResponse(sprintf('Invalid channel: %s', $notifyRequest->channel), Response::HTTP_BAD_REQUEST);
        }

        $notifier->notify($notifyRequest->message);

        return new JsonResponse('ok', Response::HTTP_OK);
    }
}
