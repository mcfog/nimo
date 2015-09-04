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
        if (is_callable($middleware)) {
            return $middleware;
        }

        throw new \InvalidArgumentException('$middleware must be a valid middleware');
    }

    public static function noopNext(
        /** @noinspection PhpUnusedParameterInspection */
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return $response;
    }

}
