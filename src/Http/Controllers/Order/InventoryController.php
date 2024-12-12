<?php

namespace Http\Controllers\Order;

use DesignPatterns\AbstractFactory\PersonRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Entities\Inventory;
use Entities\Person;
use Http\Core\Request;
use Http\Core\Response;
use Webmozart\Assert\Assert;

class InventoryController
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function index(): Response
    {
        $inventories = $this->entityManager->getRepository(Inventory::class)->findAll();

        return new Response(array_map(function (Inventory $inventory) {
            return $this->prepareResponse($inventory);
        }, $inventories), 200);
    }

    public function store(Request $request): Response
    {
        $inventory = new Inventory();

        try {
            Assert::notNull($request->getRequest('name'), 'Name is required');
            Assert::notNull($request->getRequest('quantity'), 'Quantity is required');
            Assert::notNull($request->getRequest('price'), 'Price is required');
        } catch (\InvalidArgumentException $e) {
            return new Response(['error' => $e->getMessage()], 422);
        }

        $inventory->setName($request->getRequest('name'));
        $inventory->setQuantity($request->getRequest('quantity'));
        $inventory->setPrice($request->getRequest('price'));

        $this->entityManager->persist($inventory);

        return new Response($this->prepareResponse($inventory), 201);
    }

    public function show(Request $request): Response
    {
        $inventory = $this->entityManager->getRepository(Inventory::class)->find($request->getQuery('id'));

        if (!$inventory) {
            return new Response(['error' => 'Inventory not found'], 404);
        }

        return new Response($this->prepareResponse($inventory), 200);
    }

    public function update(Request $request): Response
    {
        $inventory = $this->entityManager->getRepository(Inventory::class)->find($request->getQuery('id'));

        if (!$inventory) {
            return new Response(['error' => 'Inventory not found'], 404);
        }

        try {
            Assert::notNull($request->getRequest('name'), 'Name is required');
            Assert::notNull($request->getRequest('quantity'), 'Quantity is required');
            Assert::notNull($request->getRequest('price'), 'Price is required');
        } catch (\InvalidArgumentException $e) {
            return new Response(['error' => $e->getMessage()], 422);
        }

        $inventory->setName($request->getRequest('name'));
        $inventory->setQuantity($request->getRequest('quantity'));
        $inventory->setPrice($request->getRequest('price'));

        $this->entityManager->persist($inventory);

        return new Response($this->prepareResponse($inventory), 200);
    }

    private function prepareResponse(Inventory $inventory): array
    {
        return [
            'id' => $inventory->getId(),
            'name' => $inventory->getName(),
            'quantity' => $inventory->getQuantity(),
            'price' => $inventory->getPrice(),
        ];
    }
}
