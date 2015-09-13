<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class NimoTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function prophesizeServerRequest()
    {
        return $this->prophesize(ServerRequestInterface::class);
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function prophesizeResponse()
    {
        return $this->prophesize(ResponseInterface::class);
    }
}
