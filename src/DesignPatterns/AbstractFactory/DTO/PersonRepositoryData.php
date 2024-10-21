<?php

namespace DesignPatterns\AbstractFactory\DTO;

use Spatie\LaravelData\Data;
use Doctrine\ORM\EntityManagerInterface;

class PersonRepositoryData extends Data
{
    public function __construct(
        public string|null $filename,
        public EntityManagerInterface|null $entityManager,
    ) {
    }
}