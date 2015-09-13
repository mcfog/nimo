<?php namespace Nimo\Tests;

/**
 * User: mcfog
 * Date: 15/9/13
 */

use Nimo\AbstractMiddleware;
use Nimo\Bundled\ConditionMiddleware;
use Nimo\NimoUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ConditionMiddlewareTest extends NimoTestCase
{
    public function testFalsyCondition()
    {
        $inner = $this->prophesize(AbstractMiddleware::class);
        $inner->__call('main', [])->shouldNotBeCalled();
        $request = $this->prophesizeServerRequest()->reveal();
        $response = $this->prophesizeResponse()->reveal();

        $condition = function (
            ServerRequestInterface $req,
            ResponseInterface $res
        ) use ($request, $response) {
            $this->assertSame($request, $req);
            $this->assertSame($response, $res);

            return false;
        };

        $middleware = new ConditionMiddleware($condition, $inner->reveal());


        $returnValue = call_user_func(
            $middleware,
            $request,
            $response,
            [NimoUtility::class, 'noopNext']
        );

        $this->assertSame($response, $returnValue);
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

    public function testPrepend()
    {
        $request = $this->prophesizeServerRequest()->reveal();
        $request1 = $this->prophesizeServerRequest()->reveal();
        $response = $this->prophesizeResponse()->reveal();
        $response1 = $this->prophesizeResponse()->reveal();

        $inner = $this->prophesize(AbstractMiddleware::class);
        $inner->__call('main', [])->shouldNotBeCalled();

        $condition = function (
            ServerRequestInterface $req,
            ResponseInterface $res
        ) use ($request1, $response1, $request, $response) {
            $this->assertSame($request1, $req);
            $this->assertSame($response1, $res);

            return $request === $req || $response === $res;
        };

        $middleware = new ConditionMiddleware($condition, $inner->reveal());
        $middleware0 = function (
            ServerRequestInterface $req,
            ResponseInterface $res,
            callable $next
        ) use ($request, $response, $request1, $response1) {
            $this->assertSame($request, $req);
            $this->assertSame($response, $res);

            return $next($request1, $response1);
        };


        $returnValue = call_user_func(
            $middleware->prepend($middleware0),
            $request,
            $response,
            [NimoUtility::class, 'noopNext']
        );

        $this->assertSame($response1, $returnValue);
    }
}
