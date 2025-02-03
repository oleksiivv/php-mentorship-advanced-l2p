<?php

namespace Tests\Unit\Controllers\Order;

use Http\Controllers\Order\InventoryController;
use Http\Core\Request;
use Http\Core\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Entities\Inventory;
use PHPUnit\Framework\TestCase;

class InventoryControllerTest extends TestCase
{
    private $entityManagerMock;
    private $inventoryRepositoryMock;
    private $inventoryController;
    private $requestMock;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->inventoryRepositoryMock = $this->createMock(EntityRepository::class);
        $this->entityManagerMock
            ->method('getRepository')
            ->willReturn($this->inventoryRepositoryMock);

        $this->inventoryController = new InventoryController($this->entityManagerMock);
        $this->requestMock = $this->createMock(Request::class);
    }

    public function testIndex()
    {
        $inventory = new Inventory();
        $inventory->setId(1);
        $inventory->setName("Test Item");
        $inventory->setQuantity(10);
        $inventory->setPrice(100);

        $this->inventoryRepositoryMock->method('findAll')->willReturn([$inventory]);

        $response = $this->inventoryController->index();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testShow()
    {
        $inventory = new Inventory();
        $inventory->setId(1);

        $this->requestMock->method('getQuery')->with('id')->willReturn('1');
        $this->inventoryRepositoryMock->method('find')->with(1)->willReturn($inventory);

        $response = $this->inventoryController->show($this->requestMock);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDestroy()
    {
        $inventory = new Inventory();
        $inventory->setId(1);

        $this->requestMock->method('getQuery')->with('id')->willReturn('1');
        $this->inventoryRepositoryMock->method('find')->with(1)->willReturn($inventory);

        $response = $this->inventoryController->destroy($this->requestMock);

        $this->assertEquals(200, $response->getStatusCode());
    }
}