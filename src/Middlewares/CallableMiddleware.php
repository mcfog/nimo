<?php namespace Nimo\Middlewares;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CallableMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    protected $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * wrap the $middleware if it's a callable
     *
     * @param callable|MiddlewareInterface $middleware
     * @return MiddlewareInterface
     */
    public static function wrap($middleware): MiddlewareInterface
    {
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware;
        }

        if (is_callable($middleware)) {
            return new CallableMiddleware($middleware);
        }

        throw new \InvalidArgumentException('$middleware must be a valid middleware');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return call_user_func($this->callable, $request, $handler);
    }
}
