<?php namespace Nimo;

use Interop\Http\Server\MiddlewareInterface;
use Nimo\Middlewares\CatchMiddleware;
use Nimo\Middlewares\ConditionMiddleware;

trait MiddlewareTrait
{

    /**
     * append $middleware after this one, return the new $middlewareStack
     *
     * @param $middleware
     * @return MiddlewareStack
     */
    public function append($middleware)
    {
        $stack = new MiddlewareStack();

        return $stack
            ->append($this)
            ->append($middleware);
    }

    /**
     * prepend $middleware before this one, return the new $middlewareStack
     *
     * @param $middleware
     * @return MiddlewareStack
     */
    public function prepend($middleware)
    {
        $stack = new MiddlewareStack();

        return $stack
            ->prepend($this)
            ->prepend($middleware);
    }

    /**
     * wrap this middleware with $conditionCallback (skip this when the callback return falsy value)
     *
     * @param callable $conditionCallback ($req, $res, $next)
     * @return ConditionMiddleware
     */
    public function when(callable $conditionCallback)
    {
        return new ConditionMiddleware($conditionCallback, $this);
    }

    public function catch (callable $catcher, string $catchClass = \Throwable::class): MiddlewareInterface
    {
        return new CatchMiddleware($this, $catcher, $catchClass);
    }
}
