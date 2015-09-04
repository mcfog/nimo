<?php namespace Nimo\Bundled;

use Nimo\AbstractMiddleware;
use Nimo\IMiddleware;

/**
 * User: mcfog
 * Date: 15/9/4
 */
abstract class AbstractWrapperMiddleware extends AbstractMiddleware
{
    /**
     * @var IMiddleware
     */
    protected $innerMiddleware;

    function __construct(IMiddleware $innerMiddleware)
    {
        $this->innerMiddleware = $innerMiddleware;
    }
}
