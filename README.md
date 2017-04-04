# slim-cors-middleware
A middleware to handle Cors for multiple domains using Slim. "Access-Contro-Allow-Origin" only accepts one domain or a wildcard.  This makes it troublesome if you want to allow different domains access to your api. In order to give access to multiple domains you either need to resort to hacky .htaccess/apache shenanigans or just use a wildcard. It's an all or one approach. Most dev's wont bother coming up with a solution and go with the easy all "*" approach.

```
Access-Control-Allow-Origin: *
```

This middleware will detect the origin of a request, if its within the allowed list it will set the proper "Access-Control-Allow-Origin" value.


## Install

I don't have a composer install package.  Download this file into wherever you store your middlewares.  You can choose to add a psr-4 entry in your composer.json file or include the file manually.

```php
$app = new \Slim\App();
    
$app->add(new Middleware\CorsMiddleware([
    'https://dev.domain1.com' => ['GET', 'POST'],
    'https://dev.domain2.com' => ['GET', 'POST'],
    'https://dev.domain3.com' => ['GET']
  ]);
```

This middleware allows you to add method restrictions on a per domain basis. Below are some examples of valid configuration options. HTTP and HTTPS are considered 2 different origins.

One entry with a wildcard, this will give GET access to all domains requesting resources
```php
$app->add(new Middleware\CorsMiddleware([
  '*' => 'GET'
]);
```

This will give GET, POST and DELETE access to both http and https versions of api.domain.com
```php
$app->add(new Middleware\CorsMiddleware([
  'http://client.domain.com'  => 'GET, POST, DELETE',
  'https://client.domain.com' => 'GET, POST, DELETE'
]);
```

You can either choose to have your methods as an array ['GET', 'POST'] or string 'GET, POST'.
