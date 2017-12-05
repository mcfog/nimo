<?php namespace Nimo;

use Interop\Http\Server\MiddlewareInterface;
use Nimo\Handlers\CallableHandler;
use Nimo\Middlewares\CallableMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
class MiddlewareStack extends AbstractMiddleware
{
    /**
     * @var CallableHandler
     */
    protected $nextHandler;
    /**
     * @var MiddlewareInterface[]
     */
    protected $stack = [];
    protected $index;

    public function __construct()
    {
        $this->nextHandler = CallableHandler::wrap(function (ServerRequestInterface $request) {
            return $this->loop($request);
        });
    }


    /**
     * append $middleware
     * return $this
     * note this method would modify $this
     *
     * @param mixed $middleware
     * @return $this
     */
    public function append($middleware)
    {
        $this->stack[] = CallableMiddleware::wrap($middleware);
        return $this;
    }

    /**
     * prepend $middleware
     * return $this
     * note this method would modify $this
     *
     * @param $middleware
     * @return $this
     */
    public function prepend($middleware)
    {
        array_unshift($this->stack, CallableMiddleware::wrap($middleware));
        return $this;
    }

    protected function main(): ResponseInterface
    {
        $this->index = 0;

        return $this->loop($this->request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    protected function loop(ServerRequestInterface $request)
    {
        if (!isset($this->stack[$this->index])) {
            return $this->delegate($request);
        }

        return $this->stack[$this->index++]->process($request, $this->nextHandler);
    }
}
