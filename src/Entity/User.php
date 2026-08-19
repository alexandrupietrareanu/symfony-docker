<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\ExternalIdTrait;
use App\Entity\Traits\IdTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    use CreatedAtTrait;
    use IdTrait;
    use ExternalIdTrait;

    #[Assert\Email, Assert\NotBlank]
    #[ORM\Column(length: 128)]
    public private(set) ?string $email = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 64)]
    public private(set) ?string $firstName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 64)]
    public private(set) ?string $lastName = null;

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        return $this->id->toString();
    }
}
