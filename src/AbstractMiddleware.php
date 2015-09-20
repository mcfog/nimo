<?php namespace Nimo;

use Nimo\Bundled\ConditionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
abstract class AbstractMiddleware implements IMiddleware
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var callable
     */
    protected $next;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->next = $next ?: [NimoUtility::class, 'noopNext'];

        return $this->main();
    }

    /**
     * @return ResponseInterface
     */
    abstract protected function main();

    protected function next(ServerRequestInterface $request = null, ResponseInterface $response = null, $error = null)
    {
        return call_user_func($this->next, $request ?: $this->request, $response ?: $this->response, $error);
    }

    protected function invokeCallback(callable $callback)
    {
        return call_user_func($callback, $this->request, $this->response, $this->next);
    }

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
}
