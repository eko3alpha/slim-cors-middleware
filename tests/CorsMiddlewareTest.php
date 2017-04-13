<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../src/CorsMiddleware.php';

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Http\Headers;

use Eko3alpha\Slim\Middleware\CorsMiddleware;

class CorsMiddlewareTest extends PHPUnit_Framework_TestCase
{
    private $res;
    private $req;
    private $next;

    public function setup()
    {
        $_SERVER = [];

        $uri = Uri::createFromString('https://api.domain.com/users/');
        $headers = new Headers();
        $cookies = [];
        $env = Environment::mock();
        $serverParams = $env->all();
        $body = new Body(fopen('php://temp', 'r+'));
        $this->req = new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
        $this->res = new Response;

        $this->next = function ($req, $res) {
            return $res;
        };
    }

    public function testResultImplementsResponceInterface()
    {
        $res = $this->_invokeMiddleware(['*'=>'GET'], 'www.domain.com');
        $this->assertContains("Psr\Http\Message\ResponseInterface", class_implements($res));
    }

    public function testStringAllowedMethods()
    {
        $expect = 'GET, POST, PUT';
        $res = $this->_invokeMiddleware(['www.domain.com' => 'GET, POST, PUT'], 'www.domain.com');
        $actual = $res->getHeaderLine('Access-Control-Allow-Methods');
        $this->assertEquals($expect, $actual);
    }

    public function testArrayAllowedMethods()
    {
        $expect = 'GET, POST, PUT';
        $res = $this->_invokeMiddleware(['www.domain.com' => ['GET', 'POST', 'PUT']], 'www.domain.com');
        $actual = $res->getHeaderLine('Access-Control-Allow-Methods');
        $this->assertEquals($expect, $actual);
    }

    public function testWildcardAllowedMethods()
    {
        $expect = 'GET, POST, PUT';
        $res = $this->_invokeMiddleware(['*' => ['GET', 'POST', 'PUT']], 'www.domain.com');
        $actual = $res->getHeaderLine('Access-Control-Allow-Methods');
        $this->assertEquals($expect, $actual);
    }

    public function testNoMatchAllowedMethods()
    {
        $expect = false;
        $res = $this->_invokeMiddleware(['https://allowed.com' => ['GET', 'POST', 'PUT']], 'www.domain.com');
        $actual = $res->hasHeader('Access-Control-Allow-Methods');
        $this->assertEquals($expect, $actual);
    }

    public function testAllowOrigin()
    {
        $expect = 'www.domain.com';
        $res = $this->_invokeMiddleware(['www.domain.com' => ['GET', 'POST', 'PUT']], 'www.domain.com');
        $actual = $res->getHeaderLine('Access-Control-Allow-Origin');
        $this->assertEquals($expect, $actual);
    }

    public function testWildcardAllowOrigin()
    {
        $expect = '*';
        $res = $this->_invokeMiddleware(['*' => ['GET', 'POST', 'PUT']], 'www.domain.com');
        $actual = $res->getHeaderLine('Access-Control-Allow-Origin');
        $this->assertEquals($expect, $actual);
    }

    public function testNoMatchAllowOrigin()
    {
        $expect = false;
        $res = $this->_invokeMiddleware(['https://allowed.com' => ['GET', 'POST', 'PUT']], 'www.domain.com');
        $actual = $res->hasHeader('Access-Control-Allow-Origin');
        $this->assertEquals($expect, $actual);
    }

    private function _invokeMiddleware($config, $origin)
    {
        $_SERVER['HTTP_ORIGIN'] = $origin;
        $middleware = new CorsMiddleware($config);
        return $middleware($this->req, $this->res, $this->next);
    }
}
