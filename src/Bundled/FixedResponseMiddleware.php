<?php namespace Nimo\Bundled;

use Nimo\AbstractMiddleware;
use Psr\Http\Message\ResponseInterface;

/**
 * User: mcfog
 * Date: 15/9/4
 */
class FixedResponseMiddleware extends AbstractMiddleware
{
    protected $fixedResponse;

    function __construct(ResponseInterface $response)
    {
        $this->fixedResponse = $response;
    }

    protected function main()
    {
        return $this->next(null, $this->fixedResponse);
    }
}
