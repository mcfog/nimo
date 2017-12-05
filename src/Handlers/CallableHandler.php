<?php namespace Nimo\Handlers;

use Interop\Http\Server\RequestHandlerInterface;
use Nimo\AbstractHandler;
use Psr\Http\Message\ResponseInterface;

class CallableHandler extends AbstractHandler
{
    /**
     * @var callable
     */
    protected $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public static function wrap($handler): RequestHandlerInterface
    {
        if ($handler instanceof RequestHandlerInterface) {
            return $handler;
        }
        if (is_callable($handler)) {
            return new self($handler);
        }

        // @codeCoverageIgnoreStart
        throw new \InvalidArgumentException('$handler must be a valid handler');
        // @codeCoverageIgnoreEnd
    }

    protected function main(): ResponseInterface
    {
        return call_user_func($this->callable, $this->request);
    }
}
