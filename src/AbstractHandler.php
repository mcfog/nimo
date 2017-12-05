<?php namespace Nimo;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractHandler implements RequestHandlerInterface
{
    use HandlerTrait;

    protected $request;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        return $this->main();
    }

    /**
     * @return ResponseInterface
     */
    abstract protected function main(): ResponseInterface;
}
