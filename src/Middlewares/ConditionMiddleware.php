<?php namespace Nimo\Middlewares;

use Psr\Http\Message\ResponseInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
class ConditionMiddleware extends AbstractWrapperMiddleware
{
    /**
     * @var callable
     */
    private $conditionCallback;

    /**
     * @param callable $conditionCallback receive param similar to middleware ($req, $res, $next), return boolean
     * @param mixed $innerMiddleware the middleware to be executed while $conditionCallback returns truthy value
     */
    public function __construct(callable $conditionCallback, $innerMiddleware)
    {
        parent::__construct($innerMiddleware);
        $this->conditionCallback = $conditionCallback;
    }

    protected function main(): ResponseInterface
    {
        if ($this->invokeCallback($this->conditionCallback)) {
            $response = $this->innerMiddleware->process($this->request, $this->handler);
        } else {
            $response = $this->delegate();
        }

        return $response;
    }
}
