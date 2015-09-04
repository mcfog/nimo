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

    public function invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return $this->__invoke($request, $response, $next);
    }

    abstract protected function main();

    protected function next(ServerRequestInterface $request = null, ResponseInterface $response = null)
    {
        return call_user_func($this->next, $request ?: $this->request, $response ?: $this->response);
    }

    protected function invokeCallback(callable $callback)
    {
        return NimoUtility::invoke($callback, $this->request, $this->response, $this->next);
    }

    public function append($middleware)
    {
        $stack = new MiddlewareStack();

        return $stack
            ->append($this)
            ->append($middleware);
    }

    public function prepend($middleware)
    {
        $stack = new MiddlewareStack();

        return $stack
            ->prepend($this)
            ->prepend($middleware);
    }

    public function when(callable $conditionCallback)
    {
        return new ConditionMiddleware($conditionCallback, $this);
    }
}
