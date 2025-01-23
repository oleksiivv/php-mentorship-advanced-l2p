<?php

namespace Http\Controllers\Web;

use Doctrine\ORM\EntityManagerInterface;
use Entities\Inventory;
use Http\Core\Cookie\CookieManager;
use Http\Core\Request;
use Http\Core\Response;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

class WebController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CookieManager $cookieManager,
    ) {
    }

    public function products(Request $request): ResponseInterface
    {
        return new Response([
            'products' => $this->entityManager->getRepository(Inventory::class)->findAll(),
            'accessToken' => $this->cookieManager->getCurrentUser(),
        ], 200, 'text/html', 'products.php');
    }

    public function createProduct(Request $request): ResponseInterface
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
        $this->entityManager->flush();

        return new Response([
            'product' => $inventory,
            'accessToken' => $this->cookieManager->getCurrentUser(),
        ], 201, 'text/html', 'create_product.php');
    }

    public function showProduct(Request $request): ResponseInterface
    {
        $inventory = $this->entityManager->getRepository(Inventory::class)->find($request->getQuery('productId'));

        if (!$inventory) {
            return new Response(['error' => 'Inventory not found'], 404);
        }

        return new Response([
            'product' => $inventory,
            'accessToken' => $this->cookieManager->getCurrentUser(),
        ], 200, 'text/html', 'product.php');
    }

    public function updateProduct(Request $request): ResponseInterface
    {
        $inventory = $this->entityManager->getRepository(Inventory::class)->find($request->getRequest('id'));

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
        $this->entityManager->flush();

        return new Response([
            'product' => $inventory,
            'accessToken' => $this->cookieManager->getCurrentUser(),
        ], 200, 'text/html', 'product.php');
    }

    public function deleteProduct(Request $request): ResponseInterface
    {
        $inventoryId = $request->getQuery('id');

        if (!$inventoryId) {
            return new Response(['error' => 'Product ID is required'], 400);
        }

        $inventory = $this->entityManager->getRepository(Inventory::class)->find($inventoryId);

        if (!$inventory) {
            return new Response(['error' => 'Product not found'], 404);
        }

        $this->entityManager->remove($inventory);
        $this->entityManager->flush();

        return new Response([
            'products' => $this->entityManager->getRepository(Inventory::class)->findAll(),
            'accessToken' => $this->cookieManager->getCurrentUser(),
        ], 200, 'text/html', 'products.php');
    }
}
