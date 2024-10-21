<?php

namespace Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

trait FileTestCase
{
    protected function refreshFile(string $filename): string
    {
        if (file_exists($filename)) {
            file_put_contents($filename, '');
        } else {
            $directory = dirname($filename);
            
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            
            touch($filename);
        }

        return $filename;
    }
}