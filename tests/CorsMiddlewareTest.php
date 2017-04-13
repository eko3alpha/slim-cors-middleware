<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../src/CorsMiddleware.php';

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Eko3alpha\Slim\Middleware\CorsMiddleware;

class CorsMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testThis()
    {
        $this->assertTrue(true);
    }

    public function testThisToo()
    {
        $this->assertTrue(false);
    }
}
