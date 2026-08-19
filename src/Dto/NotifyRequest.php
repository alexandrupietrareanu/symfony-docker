<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class NotifyRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 1000)]
        public string $message,

        #[Assert\Choice(['email', 'sms', 'push'])]
        public ?string $channel = null,
    ) {
    }
}
