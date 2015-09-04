<?php namespace Nimo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
abstract class NimoUtility
{
    /**
     * @param mixed $middleware
     * @return callable
     */
    public static function wrap($middleware)
    {
        if ($middleware instanceof IMiddleware) {
            return $middleware;
        }

        if (is_callable($middleware)) {
            return $middleware;
        }

        throw new \InvalidArgumentException('$middleware must be a valid middleware');
    }

    /**
     * @param callable $callback
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public static function invoke(
        callable $callback,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        return call_user_func($callback, $request, $response, $next);
    }

    public static function noopNext(
        /** @noinspection PhpUnusedParameterInspection */
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return $response;
    }

}
