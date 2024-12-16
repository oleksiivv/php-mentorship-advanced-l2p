<?php

namespace Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="carts")
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $total;


    /**
     * @ORM\Column(type="json")
     */
    private array $inventories = [];

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Entities\User",
     *     inversedBy="carts"
     * )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function calculateTotal(): void
    {
        $this->total = 0;

        foreach ($this->inventories as $inventory) {
            $this->total += $inventory['quantity'] * $inventory['inventory']['price'];
        }
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setInventories(array $inventories): void
    {
        $this->inventories = $inventories;

        $this->calculateTotal();
    }

    public function getInventories(): array
    {
        return $this->inventories;
    }

    public function addInventory(array $inventory, int $quantity): void
    {
        $this->inventories[] = [
            'inventory' => $inventory,
            'quantity' => $quantity,
        ];

        $this->calculateTotal();
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

