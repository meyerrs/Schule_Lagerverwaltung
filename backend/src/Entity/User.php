<?php

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    private string $lastname;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::STRING)]
    private string $role = "Lehrer";

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): string
    {
        return $this->getRole();
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
