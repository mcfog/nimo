<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Nimo\AbstractMiddleware;
use Nimo\Bundled\ConditionMiddleware;
use Nimo\NimoUtility;

class ConditionMiddlewareTest extends NimoTestCase
{
    public function testFalsyCondition()
    {
        $inner = $this->prophesize(AbstractMiddleware::class);
        $inner->__call('main', [])->shouldNotBeCalled();

        $condition = function () {
            return false;
        };
        $middleware = new ConditionMiddleware($condition, $inner->reveal());

        $answerRes = $this->prophesizeResponse()->reveal();

        $returnValue = call_user_func(
            $middleware,
            $this->prophesizeServerRequest()->reveal(),
            $answerRes,
            [NimoUtility::class, 'noopNext']
        );

        $this->assertSame($answerRes, $returnValue);
    }

    public function testTruthyCondition()
    {
        $answerRes = $this->prophesizeResponse()->reveal();
        $inner = $this->prophesize(AbstractMiddleware::class);
        $inner->__call('main', [])
            ->willReturn($answerRes)
            ->shouldBeCalled();

        $condition = function () {
            return true;
        };
        $middleware = new ConditionMiddleware($condition, $inner->reveal());


        $returnValue = call_user_func(
            $middleware,
            $this->prophesizeServerRequest()->reveal(),
            $this->prophesizeResponse()->reveal(),
            [NimoUtility::class, 'noopNext']
        );

        $this->assertSame($answerRes, $returnValue);
    }
}
