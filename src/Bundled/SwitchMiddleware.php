<?php namespace Nimo\Bundled;

use Nimo\AbstractMiddleware;

/**
 * User: mcfog
 * Date: 15/9/4
 */
class SwitchMiddleware extends AbstractMiddleware
{
    /**
     * @var callable
     */
    protected $switchCallback;

    function __construct(callable $switchCallback)
    {
        $this->switchCallback = $switchCallback;
    }

    protected function main()
    {
        return $this->invokeCallback($this->invokeCallback($this->switchCallback));
    }
}
