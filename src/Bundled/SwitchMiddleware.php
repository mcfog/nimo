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

    public function __construct(callable $switchCallback)
    {
        $this->switchCallback = $switchCallback;
    }

    protected function main()
    {
        $callback = $this->invokeCallback($this->switchCallback);
        if (!is_callable($callback)) {
            throw new \RuntimeException('illegal switch result');
        }
        return $this->invokeCallback($callback);
    }
}
