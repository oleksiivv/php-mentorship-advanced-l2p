<?php

namespace Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @ORM\Column (type="string")
     */
    private string $passwordSha;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private string $accessToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $accessTokenExpiresAt;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPasswordSha(string $passwordSha): void
    {
        $this->passwordSha = $passwordSha;
    }

    public function getPasswordSha(): string
    {
        return $this->passwordSha;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function addRole(string $role): void
    {
        if (! $this->hasRole($role)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        $this->roles = array_filter($this->roles, fn ($r) => $r !== $role);
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
        $this->accessTokenExpiresAt = (new \DateTime())->modify('+1 hour');
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getAccessTokenExpiresAt(): DateTime
    {
        return $this->accessTokenExpiresAt;
    }
}
