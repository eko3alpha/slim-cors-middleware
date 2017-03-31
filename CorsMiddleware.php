<?php
namespace Middleware;

class CorsMiddleware
{
    /**
     * Associative array with domain => [allowed, methods, list]
     * @var array
     */
    protected $cors = [
        'https://www.domain.com' => ['GET', 'PUT'],
        'http://www.domain.com'  => ['GET', 'PUT'],

        'https://local.domain.com' => ['GET', 'PUT'],
        'http://local.domain.com'  => ['GET', 'PUT'],

        'https://staging.domain.com' => ['GET', 'PUT'],
        'http://staging.domain.com'  => ['GET', 'PUT']
    ];

    /**
     * Middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run($request, $response, $next)
    {
        $response = $next($request, $response);
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'none';
        return $this->getResponse($response, $origin, $this->cors);
    }

    /**
     * Gets allow method string
     * @param  string   $origin origin domain
     * @param  array    $cors   access list with methods
     * @return string           comma delimited string of methods
     */
    private function getAllowedMethodsString($origin, $cors)
    {
        $methods = $cors[$origin];
        return implode(', ', $methods);
    }

    /**
     * Gets appropriate response object
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 Response
     * @param  string                               $origin  origin domain
     * @param  array                                $cors    access list with methods
     * @return \Psr\Http\Message\ResponseInterface $response PSR7 Response
     */
    private function getResponse($response, $origin, $cors)
    {
        if (!isset($cors[$origin])) {
            return $response;
        }

        return $response
        ->withHeader('Access-Control-Allow-Origin', $origin)
        ->withHeader('Access-Control-Allow-Methods', $this->getAllowedMethodsString($origin, $cors));
    }
}
