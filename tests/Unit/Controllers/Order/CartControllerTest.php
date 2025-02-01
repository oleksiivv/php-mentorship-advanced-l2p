<?php

namespace Tests\Unit\Controllers\Order;

use Http\Controllers\Order\CartController;
use Http\Core\Request;
use Http\Core\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Entities\Cart;
use Entities\User;
use Entities\Inventory;
use PHPUnit\Framework\TestCase;

class CartControllerTest extends TestCase
{
    private EntityManagerInterface $entityManagerMock;
    private Request $requestMock;
    private EntityRepository $cartRepositoryMock;
    private EntityRepository $inventoryRepositoryMock;
    private CartController $cartController;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->requestMock = $this->createMock(Request::class);
        $this->cartRepositoryMock = $this->createMock(EntityRepository::class);
        $this->inventoryRepositoryMock = $this->createMock(EntityRepository::class);

        $this->entityManagerMock->method('getRepository')
            ->willReturnMap([
                [Cart::class, $this->cartRepositoryMock],
                [Inventory::class, $this->inventoryRepositoryMock]
            ]);

        $this->cartController = new CartController($this->entityManagerMock);
    }

    public function testCreateCart()
    {
        $user = new User();
        $this->requestMock->method('getUser')->willReturn($user);

        $this->entityManagerMock->method('persist')->willReturnCallback(function ($cart) use ($user) {
            $this->assertEquals($user, $cart->getUser());
        });

        $response = $this->cartController->create($this->requestMock);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testAddToCart()
    {
        $user = new User();
        $cart = new Cart();
        $inventory = new Inventory();
        $user->setId(1);
        $cart->setUser($user);
        $inventory->setId(1);

        $this->requestMock->method('getUser')->willReturn($user);
        $this->requestMock->method('getRequest')->willReturn('1');

        $this->cartRepositoryMock->method('find')->with(1)->willReturn($cart);
        $this->inventoryRepositoryMock->method('find')->with(1)->willReturn($inventory);

        $response = $this->cartController->addToCart($this->requestMock);

        $this->assertEquals(200, $response->getStatusCode());
    }

    // Additional test methods for removeFromCart, checkout should follow the same structure.

    public function testCheckout()
    {
        $user = new User();
        $cart = new Cart();
        $user->setId(1);
        $cart->setUser($user);
        $this->requestMock->method('getUser')->willReturn($user);
        $this->requestMock->method('getRequest')->with('cart_id')->willReturn('123');
        $this->cartRepositoryMock->method('find')->with(123)->willReturn($cart);

        $response = $this->cartController->checkout($this->requestMock);

        $this->assertEquals(200, $response->getStatusCode());
        // Further assertions can be performed on the response content.
    }
}