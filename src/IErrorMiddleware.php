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
    /**
     * the error handler middleware receive $error as first param, and ($req, $res, $next) after that.
     * it should handle the $error, or pass it to $next while it can't handle that
     * it should return the response
     * it might use the $next to continue the chain, and might use the return value of $next as the result response
     *
     * @param $error
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(
        $error,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    );
}