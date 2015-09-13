<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Nimo\AbstractMiddleware;
use Nimo\NimoUtility;
use Prophecy\Argument;

class AbstractMiddlewareTest extends NimoTestCase
{
    public function testMiddleware()
    {
        $responseProphecy = $this->prophesizeResponse();
        $answerRes = $responseProphecy->reveal();
        $req = $this->prophesizeServerRequest()->reveal();
        $res = $responseProphecy->reveal();

        $mProphecy = $this->prophesize()->willExtend(AbstractMiddleware::class);
        $mProphecy->__call('main', [])
            ->will(function ($args, $obj, $method) use ($req, $res, $answerRes) {
                return $answerRes;
            });

        $middleware = $mProphecy->reveal();

        /** @noinspection PhpParamsInspection */
        $returnValue = call_user_func($middleware, $req, $res, [NimoUtility::class, 'noopNext']);

        $this->assertSame($answerRes, $returnValue);
    }

}
