<?php
namespace Eko3alpha\Slim\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CorsMiddleware
{
    protected $cors;

    public function __construct($cors)
    {
        $this->cors = $cors;
    }

/**
 * Middleware invokable class
 *
 * @param  RequestInterface     $request  PSR7 request
 * @param  ResponseInterface    $response PSR7 response
 * @param  callable             $next     Next middleware
 *
 * @return ResponseInterface
 */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response = $next($request, $response);
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'none';
        return $this->getResponse($response, $origin, $this->cors);
    }

/**
* Gets allow method string
* @param  array    $cors   access list with methods
* @param  string   $origin origin domain
* @return string           comma delimited string of methods
*/
    private function getAllowedMethodsString($cors, $origin)
    {
        $methods = $cors[$origin];
        if (is_array($methods)) {
            $methods = implode(', ', $methods);
        }
        return $methods;
    }

/**
 * Gets the proper origin header value
 * @param  array    $cors   cors config
 * @param  string   $origin http_origin
 * @return string           origin value
 */
    private function getOriginHeader($cors, $origin)
    {
        if (isset($cors['*'])) {
            return '*';
        }
        return $origin;
    }

/**
 * Gets appropriate response object
 * @param  ResponseInterface    $response   PSR7 Response
 * @param  string               $origin     origin domain
 * @param  array                $cors       access list with methods
 * @return ResponseInterface                PSR7 Response
 */
    private function getResponse(ResponseInterface $response, $origin, $cors)
    {

        if (isset($cors['*'])) {
            $origin = '*';
        }

        if (!isset($cors[$origin])) {
            return $response;
        }

        return $response
        ->withHeader('Access-Control-Allow-Origin', $this->getOriginHeader($cors, $origin))
        ->withHeader('Access-Control-Allow-Methods', $this->getAllowedMethodsString($cors, $origin));
    }
}
