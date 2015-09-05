<?php namespace Nimo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
class MiddlewareStack extends AbstractMiddleware
{
    /**
     * @var ServerRequestInterface
     */
    protected $currentRequest;

    /**
     * @var ResponseInterface
     */
    protected $currentResponse;
    /**
     * @var callable[]
     */
    protected $stack = [];
    protected $index;

    public function append($middleware)
    {
        $this->stack[] = NimoUtility::wrap($middleware);
        return $this;
    }

    public function prepend($middleware)
    {
        array_unshift($this->stack, NimoUtility::wrap($middleware));
        return $this;
    }

    protected function main()
    {
        $this->index = 0;

        $this->currentResponse = $this->_loop($this->request, $this->response);

        return $this->next($this->currentRequest, $this->currentResponse);
    }

    /**
     * @internal
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param mixed $error
     * @return ResponseInterface
     */
    public function _loop(ServerRequestInterface $request, ResponseInterface $response, $error = null)
    {
        $this->currentRequest = $request;
        $this->currentResponse = $response;

        if (!isset($this->stack[$this->index])) {
            return $response;
        }

        $currentMiddleware = $this->stack[$this->index];
        $atErrorMiddleware = $currentMiddleware instanceof IErrorMiddleware;
        $isError = !is_null($error);

        $this->index++;

        if ($isError ^ $atErrorMiddleware) {
            return $this->_loop($request, $response, $error);
        }

        $args = [
            $request,
            $response,
            [$this, '_loop']
        ];

        if ($isError) {
            array_unshift($args, $error);
        }

        try {
            return call_user_func_array($currentMiddleware, $args);
        } catch (\Exception $e) {
            $error = $e;
            return $this->_loop($request, $response, $error);
        }
    }
}
