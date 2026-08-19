<?php

namespace App\Entity\Traits;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\DatePointType;
use Symfony\Component\Clock\DatePoint;

trait CreatedAtTrait
{
    #[ApiProperty(writable: false)]
    #[ORM\Column(type: DatePointType::NAME)]
    public private(set) DatePoint $createdAt;
}
