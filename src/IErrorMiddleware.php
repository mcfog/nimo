<?php
/**
 * User: mcfog
 * Date: 15/9/4
 */


namespace Nimo;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface IErrorMiddleware
{
    public function __invoke(
        $error,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    );
}