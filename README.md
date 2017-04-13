# slim-cors-middleware ([Slim v3.x](https://github.com/slimphp/Slim/))

[![Packagist](https://img.shields.io/packagist/v/eko3alpha/slim-cors-middleware.svg?style=flat-square)](https://packagist.org/packages/eko3alpha/slim-cors-middleware)
[![Packagist](https://img.shields.io/packagist/dt/eko3alpha/slim-cors-middleware.svg)](https://packagist.org/packages/eko3alpha/slim-cors-middleware)


A middleware to handle Cors for multiple domains using Slim. "Access-Contro-Allow-Origin" only accepts one domain or a wildcard.  This makes it troublesome if you want to allow different domains access to your api. In order to give access to multiple domains you either need to resort to hacky .htaccess/apache shenanigans or just use a wildcard. It's an all or one approach. Most dev's wont bother coming up with a solution and go with the easy all "*" approach.

```
Access-Control-Allow-Origin: *
```

This middleware will detect the origin of a request, if its within the allowed list it will set the proper "Access-Control-Allow-Origin" value for that domain.

```
Access-Control-Allow-Origin: https://client.domain.com
```

## Install

You can either download manually or use composer.

```
composer require eko3alpha/slim-cors-middleware
```

## Usage

```php
$app = new \Slim\App();

$app->add(new \Eko3alpha\Slim\Middleware\CorsMiddleware([
    'https://dev.domain1.com' => ['GET', 'POST'],
    'https://dev.domain2.com' => ['GET', 'POST'],
    'https://dev.domain3.com' => ['GET']
  ]);
```
## Examples

This middleware allows you to add method restrictions on a per domain basis. Below are some examples of valid configuration options. HTTP and HTTPS are considered 2 different origins.

One entry with a wildcard, this will give GET access to all domains requesting resources
```php
$app->add(new \Eko3alpha\Slim\Middleware\CorsMiddleware([
  '*' => 'GET'
]);
```

This will give GET, POST and DELETE access to both http and https versions of api.domain.com, you can either use a string value or array.
```php
$app->add(new \Eko3alpha\Slim\Middleware\CorsMiddleware([
  'http://client.domain.com'  => 'GET, POST, DELETE',
  'https://client.domain.com' => ['GET', 'POST', 'DELETE']
]);
```

You can either choose to have your methods as an array ['GET', 'POST'] or string 'GET, POST'.


## Slim Container

You can use Slim's container to hold the configuration if you prefer to have your configuration in a seperate file.

```php
$container = new Slim\Container;

.
.
.

$container['cors'] = ['*' => 'GET, POST'];

.
.
.

$app->add(new \Eko3alpha\Slim\Middleware\CorsMiddleware($container['cors']);
```





