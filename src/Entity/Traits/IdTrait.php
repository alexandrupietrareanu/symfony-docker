<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

trait IdTrait
{
    #[ORM\Column(type: UuidType::NAME), ORM\Id]
    public private(set) Uuid $id;
}
