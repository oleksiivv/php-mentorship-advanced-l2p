<?php

namespace Http\Controllers\Order;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Entities\Cart;
use Entities\Inventory;
use Entities\Person;
use Http\Core\Request;
use Http\Core\Response;
use Webmozart\Assert\Assert;

class CartController
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function create(Request $request): Response
    {
        $user = $request->getUser();
        try {
            Assert::notNull($user);
        } catch (\InvalidArgumentException $e) {
            return new Response(['error' => $e->getMessage()], 422);
        }

        $cart = new Cart();
        $cart->setUser($user);

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return new Response($this->prepareResponse($cart), 201);
    }

    public function addToCart(Request $request): Response
    {
        $user = $request->getUser();

        try {
            Assert::notNull($request->getRequest('cart_id'), 'Cart ID is required');
            Assert::notNull($request->getRequest('inventory_id'), 'Inventory ID is required');
            Assert::notNull($request->getRequest('quantity'), 'Quantity is required');
            Assert::notNull($user);
        } catch (\InvalidArgumentException $e) {
            return new Response(['error' => $e->getMessage()], 422);
        }

        $cart = $this->entityManager->getRepository(Cart::class)->find($request->getRequest('cart_id'), lockMode: LockMode::PESSIMISTIC_WRITE);

        if (!$cart) {
            return new Response(['error' => 'Cart not found'], 404);
        }

        if ($cart->getUser()->getId() !== $user->getId()) {
            return new Response(['error' => 'Unauthorized'], 401);
        }

        $inventory = $this->entityManager->getRepository(Inventory::class)->find($request->getRequest('inventory_id'), lockMode: LockMode::PESSIMISTIC_WRITE);

        if (!$inventory) {
            return new Response(['error' => 'Inventory not found'], 404);
        }

        $this->entityManager->beginTransaction();
        try {
            $cart->addInventory($inventory, $request->getRequest('quantity'));
            $inventory->setQuantity($inventory->getQuantity() - $request->getRequest('quantity'));

            $this->entityManager->persist($cart);
            $this->entityManager->persist($inventory);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return new Response(['error' => $e->getMessage()], 500);
        }

        return new Response($this->prepareResponse($cart), 200);
    }

    public function removeFromCart(Request $request): Response
    {
        $user = $request->getUser();

        try {
            Assert::notNull($request->getRequest('cart_id'), 'Cart ID is required');
            Assert::notNull($request->getRequest('inventory_id'), 'Inventory ID is required');
            Assert::notNull($user);
        } catch (\InvalidArgumentException $e) {
            return new Response(['error' => $e->getMessage()], 422);
        }

        $cart = $this->entityManager->getRepository(Cart::class)->find($request->getRequest('cart_id'), lockMode: LockMode::PESSIMISTIC_WRITE);

        if (!$cart) {
            return new Response(['error' => 'Cart not found'], 404);
        }

        if ($cart->getUser()->getId() !== $user->getId()) {
            return new Response(['error' => 'Unauthorized'], 401);
        }

        $inventory = $this->entityManager->getRepository(Inventory::class)->find($request->getRequest('inventory_id'), lockMode: LockMode::PESSIMISTIC_WRITE);

        if (!$inventory) {
            return new Response(['error' => 'Inventory not found'], 404);
        }

        $this->entityManager->beginTransaction();

        try {
            $cart->setInventories(array_filter($cart->getInventories(), function ($cartInventory) use ($inventory, $request) {
                return $cartInventory['inventory']['id'] !== $inventory->getId();
            }));

            $inventory->setQuantity($inventory->getQuantity() + $request->getRequest('quantity'));

            $this->entityManager->persist($cart);
            $this->entityManager->persist($inventory);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return new Response(['error' => $e->getMessage()], 500);
        }

        return new Response($this->prepareResponse($cart), 200);
    }

    public function checkout(Request $request): Response
    {
        $user = $request->getUser();

        try {
            Assert::notNull($request->getRequest('cart_id'), 'Cart ID is required');
            Assert::notNull($user);
        } catch (\InvalidArgumentException $e) {
            return new Response(['error' => $e->getMessage()], 422);
        }

        $cart = $this->entityManager->getRepository(Cart::class)->find(id: $request->getRequest('cart_id'), lockMode: LockMode::PESSIMISTIC_WRITE);

        if (!$cart) {
            return new Response(['error' => 'Cart not found'], 404);
        }

        if ($cart->getUser()->getId() !== $user->getId()) {
            return new Response(['error' => 'Unauthorized'], 401);
        }

        return new Response($this->prepareResponse($cart), 200);
    }

    private function prepareResponse(Cart $cart): array
    {
        return [
            'id' => $cart->getId(),
            'total' => $cart->getTotal(),
            'inventories' => array_map(function ($inventory) {
                return [
                    'id' => $inventory['inventory']['id'],
                    'name' => $inventory['inventory']['name'],
                    'quantity' => $inventory['quantity'],
                    'price' => $inventory['inventory']['price'],
                ];
            }, $cart->getInventories())
        ];
    }
}
