<?php namespace Nimo\Bundled;

use Nimo\IErrorMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
class CallbackErrorMiddleware implements IErrorMiddleware
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(
        $error,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        return call_user_func($this->callback, $error, $request, $response, $next);
    }
}
