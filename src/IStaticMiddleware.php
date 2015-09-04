<?php namespace Nimo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
interface IStaticMiddleware
{
    public static function middleware(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    );
}
