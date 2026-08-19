<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ExternalIdTrait
{
    #[Assert\Length(max: 36)]
    #[Assert\NotBlank]
    #[ORM\Column(length: 36)]
    public private(set) ?string $externalId = '';
}
