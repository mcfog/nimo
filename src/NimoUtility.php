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
     * wrap the $middleware
     * currently simple check callable
     * might be adapter method if more than one middleware standard is supported in future
     *
     * @param mixed $middleware
     * @return callable
     */
    public static function wrap($middleware)
    {
        if (is_callable($middleware)) {
            return $middleware;
        }

        throw new \InvalidArgumentException('$middleware must be a valid middleware');
    }

    /**
     * a basic $next callback simply return the $response in param
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public static function noopNext(
        /** @noinspection PhpUnusedParameterInspection */
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return $response;
    }

}
